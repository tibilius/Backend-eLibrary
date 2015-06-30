<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Library\CatalogBundle\DBAL\Types\ReadlistEnumType;
use Library\CatalogBundle\Entity\Readlists;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150628142830 extends AbstractMigration implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE readlists DROP CONSTRAINT readlists_type_check');
        $this->addSql('ALTER TABLE readlists ADD CONSTRAINT readlists_type_check
          CHECK (type::text = ANY (ARRAY[
          \'in_read\'::character varying::text,
          \'my_library\'::character varying::text,
          \'readed\'::character varying::text,
          \'paused\'::character varying::text,
          \'usual\'::character varying::text]
          ))');
    }

    /**
     * @param Schema $schema
     */
    public function postUp(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $em = $this->container->get('doctrine.orm.entity_manager');
        $users = $em->getRepository('UserBundle:User')->findAll();
        $types = ReadlistEnumType::getInternalTypes();
        foreach ($users as $user) {
            $readlists = $em->getRepository('CatalogBundle:Readlists')->findBy(['user' => $user->getId(), 'type' => $types]);
            foreach($readlists as $entity) {
                $types = array_diff($types, [$entity->getType()]);
            }
            if (!$types) {
                continue;
            }
            foreach($types as $type) {
                $readlist =  new Readlists();
                $readlist
                    ->setUser($user)
                    ->setType($type)
                    ->setName(ReadlistEnumType::getChoices()[$type])
                    ->setColor(ReadlistEnumType::getColors()[$type]);
                $em->persist($readlist);
            }
        }
        $em->flush();

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
