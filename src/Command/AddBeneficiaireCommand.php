<?php

namespace App\Command;

use App\Entity\Beneficiaire;
use App\Model\TypeBeneficiaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:add-beneficiaire',
    description: 'AJouter un bénéficiaire',
)]
class AddBeneficiaireCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('nom', InputArgument::REQUIRED, 'Nom du bénéficiaire à ajouter');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $beneficiaire = new Beneficiaire();
        $nom = $input->getArgument('nom');
        $beneficiaire->setNom($nom);

        $question = new ChoiceQuestion(
            'Type de bénéficiaire à créer ? ',
            array_map(fn ($element): string => $element->value, TypeBeneficiaire::cases())
        );
        $type = $helper->ask($input, $output, $question);
        $beneficiaire->setType(TypeBeneficiaire::from($type));

        $this->entityManager->persist($beneficiaire);
        $this->entityManager->flush();

        $io->success("Bénéficiaire $nom ajouté.");

        return Command::SUCCESS;
    }
}
