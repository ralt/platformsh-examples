<?php

namespace Sandbox\MainBundle\DataFixtures\PHPCR;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\PHPCR\DocumentManager;

use PHPCR\Util\NodeHelper;

use Symfony\Component\DependencyInjection\ContainerAware;

use Symfony\Cmf\Bundle\SimpleCmsBundle\Doctrine\Phpcr\Page;

class LoadSimpleCmsData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
{
    public function getOrder()
    {
        return 50;
    }

    public function load(ObjectManager $manager)
    {
        $basepath = $this->container->getParameter('cmf_simple_cms.persistence.phpcr.basepath');
        $root = $manager->find(null, $basepath);
        $root->setTitle('simple cms root (hidden by the home route in the sandbox)');

        $this->createPage($manager, $root, 'about', 'About us', 'Some information about us', 'The about us page with some content');
        $this->createPage($manager, $root, 'contact', 'Contact', 'A contact page', 'Please send an email to cmf-devs@groups.google.com');

        $manager->flush();
    }

    /**
     * @return Page instance with the specified information
     */
    protected function createPage(DocumentManager $manager, $parent, $name, $label, $title, $body)
    {
        $page = new Page();
        $page->setPosition($parent, $name);
        $page->setLabel($label);
        $page->setTitle($title);
        $page->setBody($body);

        $manager->persist($page);

        return $page;
    }
}
