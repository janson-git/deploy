<?php

namespace Service\Util;

use Admin\App;

class Fs
{
    private static ?self $instance = null;

    protected string $workDir;

    public function __construct(?string $workDir = null)
    {
        $this->workDir = $workDir ?: ROOT_DIR;
    }
    
    public static function i(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function ensureDir(string $dirName): bool
    {
        $absolutePath = $this->getAbsolutePath($dirName);
        
        if (!file_exists($absolutePath)) {
            mkdir($absolutePath, 0777, true);
            chmod($absolutePath, 0777);
        }
        
        return file_exists($absolutePath);
    }
    
    public function hasFile(string $fileName): bool
    {
        return file_exists($this->getAbsolutePath($fileName));
    }
    
    public function writeFile(string $fileName, string $fileBody): bool
    {
        return (bool) file_put_contents($this->getAbsolutePath($fileName), $fileBody);
    }
    
    public function exec($cmd, &$out, &$result, $from, $outLinesLimit = null)
    {
        $out   = [];
        $start = microtime(1);
        try {
            $fp = popen('cd ' . $this->workDir . ' && ' . $cmd . ' 2>&1', "r");
            
            $outString = '';
            while (!feof($fp)) {
                $outString .= fread($fp, 2048);
            }
            $result = pclose($fp);
            
            $outData = explode("\n", $outString);
            
            foreach ($outData as $id => &$line) {
                $line = trim($line);
                if ($line) {
                    $out[] = $line;
                }
            }
            
            if ($outLinesLimit && $out && count($out) > $outLinesLimit) {
                $halfSlice = ($outLinesLimit)/2;
                $out = 
                    array_merge(
                        array_slice($out, 0 , ceil($halfSlice)),
                        ['... skipped '.(count($out) - $outLinesLimit) .' lines ...'],
                        array_slice($out, -floor($halfSlice))
                    );
            }

            $lastLine = end($out);
            
            $msg = $cmd
                . '  | '
                . ($result !== 0 ? 'Fail: ' . implode(' // ', array_slice($out, 0, 10)) : 'Success')
                . ' | '
                . $this->workDir;
            App::i()->log($msg, $from, $start);
        } catch (\Exception $e) {
            App::i()->log($cmd . ' with exception: ' . $e->getMessage(), $from, $start);
            throw $e;
        }
        
        return $lastLine;
    }
    
    public function silentExec($cmd, $from)
    {
        $this->exec($cmd, $out, $result, $from);
        return $result === 0;
    }
    
    public function stdExec($cmd, $from, $outLinesLimit = 10)
    {
        $start = microtime(1);
        $this->exec($cmd, $out, $result, $from);
    
    
        if ($outLinesLimit && $out && count($out) > $outLinesLimit) {
            $halfSlice = ($outLinesLimit)/2;
            $out =
                array_merge(
                    array_slice($out, 0 , ceil($halfSlice)),
                    ['... skipped '.(count($out) - $outLinesLimit) .' lines ...'],
                    array_slice($out, -floor($halfSlice))
                );
        }
        
        return [
            'result' => $result !== 0 ? "Fail" : "Success",
            'cmd'    => $cmd,
            'out'    => $out,
            'time'   => round(microtime(1) - $start, 4),
        ];
    }
    
    public function rmLink($targetPath, $from): bool
    {
        $this->exec('rm ' . $targetPath, $out, $res, $from);
        return !$res;
    }

    public function setWorkDir(string $workDir): self
    {
        $this->workDir = $workDir;
        return $this;
    }

    private function getAbsolutePath(string $fileName): string
    {
        $fileName = trim($fileName, '/');
        return $this->workDir . DIRECTORY_SEPARATOR . $fileName;
    }
}
