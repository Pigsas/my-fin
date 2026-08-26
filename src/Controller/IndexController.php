<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(Connection $connection, EntityManagerInterface $entityManager): Response
    {
        $params = $connection->getParams();
        $dbPath = $params['path'] ?? null;

        if ($dbPath === null) {
            throw new \RuntimeException('No SQLite database path configured.');
        }

        // Make sure the directory exists so SQLite can create the file in it
        $dir = dirname($dbPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        // Running any query (like listTableNames) will auto-create the SQLite file if missing
        $schemaManager = $connection->createSchemaManager();
        $existingTables = $schemaManager->listTableNames();

        if (empty($existingTables)) {
            $schemaTool = new SchemaTool($entityManager);
            $metadata = $entityManager->getMetadataFactory()->getAllMetadata();

            if (!empty($metadata)) {
                $schemaTool->createSchema($metadata);
            }
        }

        if ($entityManager->getRepository(User::class)->findOneBy([])) {
            return $this->redirectToRoute('sylius_admin_ui_login');
        } else {
            return $this->redirectToRoute('app_register');
        }
    }
}
