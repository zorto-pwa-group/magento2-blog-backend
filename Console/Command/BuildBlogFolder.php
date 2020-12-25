<?php
namespace ZT\Blog\Console\Command;

use Exception;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Component\ComponentRegistrarInterface;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Module\Dir\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BuildBlogFolder extends Command
{
    /**
     * @var DirectoryList
     */
    protected $_dir;

    /**
     * @var Reader
     */
    protected $reader;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var ComponentRegistrarInterface
     */
    protected $componentRegistrar;

    /**
     * BuildBlogFolder constructor.
     * @param DirectoryList $dir
     * @param ComponentRegistrarInterface $componentRegistrar
     * @param Filesystem $filesystem
     */
    public function __construct(
        DirectoryList $dir,
        ComponentRegistrarInterface $componentRegistrar,
        Filesystem $filesystem
    )
    {
        $this->_dir = $dir;
        $this->filesystem = $filesystem;
        $this->componentRegistrar = $componentRegistrar;
        parent::__construct();
    }


    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('pwa:blog:build');
        $this->setDescription('Build pwa blog folder.');
        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return null|int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>'.__("Start building PWA Blog !!").'</info>');
        $startTime = microtime(true);
        /** deploy webapp */
        try {
            $moduleDir = $this->componentRegistrar->getPath(ComponentRegistrar::MODULE, 'ZT_Blog');
            $sourceDir = $moduleDir . '/build';
            if (is_dir($sourceDir)) {
                $appDir = BP . '/blog';
                $this->rrmdir($appDir);
                $this->xcopy($sourceDir, $appDir, 0775);

                $appDir = BP . '/pub/blog';
                $this->rrmdir($appDir);
                $this->xcopy($sourceDir, $appDir, 0775);
            }

        }catch (Exception $e){
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
        $resultTime = microtime(true) - $startTime;
        $resultTime = round($resultTime,2).'s';
        $output->writeln('<info>' . __("Execution time :") . $resultTime . '</info>');
        $output->writeln('<info>' . __("PWA Blog was build completed successfully!!") . '</info>');
    }

    /**
     * @param $source
     * @param $dest
     * @param int $permissions
     * @return bool
     */
    public static function xcopy($source, $dest, $permissions = 0755) {
        // Check for symlinks
        if (is_link($source)) {
            return symlink(readlink($source), $dest);
        }

        // Simple copy for a file
        if (is_file($source)) {
            return copy($source, $dest);
        }

        // Make destination directory
        if (!is_dir($dest)) {
            mkdir($dest, $permissions);
        }

        // Loop through the folder
        $dir = dir($source);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            // Deep copy directories
            self::xcopy("$source/$entry", "$dest/$entry", $permissions);
        }

        // Clean up
        $dir->close();
        return true;
    }

    /**
     * Delete files and sub folders
     * @param $dir
     */
    public static function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir."/".$object))
                        self::rrmdir($dir."/".$object);
                    else
                        unlink($dir."/".$object);
                }
            }
            rmdir($dir);
        }
    }

    protected function isRootDirIsInPub(){

    }
}