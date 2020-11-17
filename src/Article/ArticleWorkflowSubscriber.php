<?php


namespace App\Article;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class ArticleWorkflowSubscriber implements EventSubscriberInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            'workflow.article_publishing.completed.editor_refused' => 'onRefused'
        ];
    }

    public function onRefused(Event $event)
    {
        # Quand est article est refusé, il est automatiquement supprimé.
        $this->manager->remove($event->getSubject());
    }
}