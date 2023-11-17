<?php

namespace Service;

use Admin\App;
use Exceptions\NotFoundException;

class Project
{
    /** @var int  */
    private $id;
    /** @var string */
    private $name;

    /** @var string[] */
    private $projectRootDirs;

    /** @var string[] */
    private $dirSets = [];
    
    /** @var Node */
    private $node;
    
    /** @var SlotsPool */
    private $slotsPool;

    public function __construct() {}

    public static function getById(int $id): self
    {
        $project = new self();
        $project->setId($id);

        return $project->loadBy($id);
    }

    private function loadBy(int $id): self
    {
        /* load project data */
        $projectsData = (new Data(App::DATA_PROJECTS))->setReadFrom(__METHOD__)->readCached();
        if (!isset($projectsData[$id])) {
            throw new NotFoundException('Project #' . $this->id . ' not found');
        }
        
        /* get project data */
        $this->projectRootDirs = $projectsData[$this->id];

        /* boot node */
        $this->node = new Node();
        $this->node->setDirs($this->projectRootDirs);
    
        $this->slotsPool = new SlotsPool();
        $this->slotsPool->setProjectId($this->id);

        return $this;
    }

    public function getNode(): ?Node
    {
        return $this->node;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getDirSets(): array
    {
        return $this->dirSets;
    }

    public function getProjectRootDirs(): ?array
    {
        return $this->projectRootDirs;
    }

    public function setProjectRootDirs(array $dirs): self
    {
        $this->projectRootDirs = $dirs;
        return $this;
    }

    public function getPaths()
    {
        return $this->projectRootDirs;
    }
    
    /**
     * @return Pack[]
     */
    public function getPacks(): array
    {
        $packs = [];
        $packsData = (new Data(App::DATA_PACKS))->setReadFrom(__METHOD__)->readCached();
        foreach ($packsData as $id => $data) {
            if ($data['project'] == $this->id) {
                $packs[] = Pack::getById($id);
            }
        }

        return $packs;
    }
    
    public function getName($withId = true): string
    {
        if (!$this->name) {
            $rootDirs = $this->projectRootDirs;
            array_walk($rootDirs, function (&$val) {
                $val = trim($val, '/');
            });
            $this->name = implode(', ', $rootDirs);
        }
        
        return $this->name . ($withId ? ' #' . $this->id : '');
    }
    
    public function getNameQuoted(): string
    {
        return preg_replace('/\W+/', '-', $this->getName());
    }

    public function getSlotsPool(): SlotsPool
    {
        return $this->slotsPool;
    }

    public function save(): void
    {
        $projectsData = new Data(App::DATA_PROJECTS);
        $projectsData->setReadFrom(__METHOD__);

        $dirs = $this->projectRootDirs;
        sort($dirs);

        $projectId = $this->getId() ?? crc32(implode(',', $this->projectRootDirs));
        $this->id = $projectId;

        $projectsData->setData($projectsData->read() + [$projectId => $dirs]);
        $projectsData->write(false);
    }
}
