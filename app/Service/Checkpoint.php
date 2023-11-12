<?php

namespace Service;

use Commands\Command\CommandProto;

/**
 * Checkpoint mapped to branch in repository
 */
class Checkpoint
{
    /** @var Pack */
    protected $pack;
    
    /** @var CommandProto[] */
    protected $commands = [];
    
    /**
     * Branch name used as checkpoint ID
     * @var string
     */
    protected $id;

    public function __construct(Pack $pack, string $id)
    {
        $this->pack = $pack;
        $this->id = $id;
    }
    
    public function getName(): string
    {
        return $this->id; 
    }
    
    public function getPack(): Pack
    {
        return $this->pack;
    }

    /**
     * @return CommandProto[]
     */
    public function getCommands(): array
    {
        return $this->commands;
    }
    
    /**
     * @param CommandProto[] $commands
     */
    public function setCommands(array $commands): self
    {
        foreach ($commands as $command) {
            $command->getContext()->setCheckpoint($this);
        }
        
        $this->commands = $commands;

        return $this;
    }
    
    public function getBuildPath(): string
    {
        $projectName = $this->pack->getProject()->getNameQuoted();
        $checkpointName = $this->id;
        
        return "builds/{$projectName}/{$checkpointName}";
    }
}