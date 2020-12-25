<?php
namespace ZT\Blog\Console\Command;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use ZT\Blog\Model\ResourceModel\Post\Collection;
use ZT\Blog\Model\ResourceModel\Post\CollectionFactory;
use ZT\Blog\Helper\Image;

class GenerateBlogImage extends Command
{
    const DIMENSION = [[80,80],[60,60]];
    /**
     * @var Collection
     */
    protected $_collection;

    protected $_blogImagehelper;

    public function __construct(
        CollectionFactory $postCollectionFactory,
        Image $imageHelper
    ) {
        $this->_collection = $postCollectionFactory->create();
        $this->_blogImagehelper = $imageHelper;
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('pwa:blog:generate-image');
        $this->setDescription('Generate images from blog post thumbnail.');
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
        try {
            $items = $this->_collection->getItems();
            foreach ($items as $post) {
                foreach (self::DIMENSION as $dimension) {
                    $this->_blogImagehelper->init($post->getFeaturedImg())->resize($dimension[0], $dimension[1]);
                }
            }

        }catch (Exception $e){
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
        $resultTime = microtime(true) - $startTime;
        $resultTime = round($resultTime,2).'s';
        $output->writeln('<info>' . __("Execution time :") . $resultTime . '</info>');
        $output->writeln('<info>' . __("PWA Blog was build completed successfully!!") . '</info>');
    }
}