<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:truncate-database',
    description: 'Trunca todas las tablas de la base de datos',
)]
class TruncateDatabaseCommand extends Command
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connection = $this->em->getConnection();

        // Desactivar claves foráneas temporalmente
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 0');

        $purger = new ORMPurger($this->em);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $purger->purge();

        // Reactivar claves foráneas
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 1');

        $output->writeln('<info>🔥 Todas las tablas fueron truncadas exitosamente.</info>');

        return Command::SUCCESS;
    }
}
