<?php

declare(strict_types=1);

namespace App\Command;

use App\Message\SaveProductMessage;
use App\Parser\ParserStrategy;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'app:parse-products')]
class ParseProductsCommand extends Command
{
    public function __construct(
        private ParserStrategy $parserStrategy,
        private MessageBusInterface   $bus
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Parse products');

        $urls = [
            'https://www.yakaboo.ua/ua/knigi/hudozhestvennaja-literatura/aforizmy-i-citaty.html',
            'https://www.yakaboo.ua/ua/knigi/komp-juternaja-literatura.html',
            'https://www.yakaboo.ua/ua/knigi/nauka-i-tehnika.html',
        ];


        foreach ($urls as $url) {

            $io->section($url);

            $parser = $this->parserStrategy->getParser($url);
            foreach ($parser->parse($url) as $item) {
                $this->bus->dispatch(new SaveProductMessage($item));
            }
        }

        $io->success('Done.');

        return Command::SUCCESS;
    }
}
