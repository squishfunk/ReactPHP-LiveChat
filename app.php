<?php

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ChatClient extends Command
{
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = "merge-table";
	
	protected function configure()
	{
		$this->setName('x');
		$this->setDescription('Symulacja klienta chatowego');
	}
	
	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$output->writeln('Witaj w symulacji klienta chatowego!');
		
		$chat = [];
		
		while (true) {
			$this->commandPrompt($input, $output);
		}
		
		return Command::SUCCESS;
	}
	
	private function commandPrompt(InputInterface $input, OutputInterface $output)
	{
		$helper = $this->getHelper('question');
		
		$commandPromptQuestion = new Question('> ');
		
		$message = $helper->ask($input, $output, $commandPromptQuestion);
		
		if (str_starts_with($message, '/')) {
			// Obsługa komend
			$this->handleCommand($message, $output);
		} else {
			$this->sendMessage($message, $output);
		}
	}
	
	private function handleCommand($command, OutputInterface $output)
	{
		switch ($command) {
			case '/help':
				$output->writeln('Dostępne komendy:');
				$output->writeln('/help - Wyświetla dostępne komendy');
				$output->writeln('/exit - Wyłącza klienta');
				// Dodaj inne komendy według potrzeb
				break;
			case '/exit':
				$output->writeln('Klient zamyka się.');
				exit();
			default:
				$output->writeln('Nieznana komenda. Wpisz "/help" aby zobaczyć dostępne komendy.');
		}
	}
	
	private function sendMessage($message, OutputInterface $output)
	{
		$message_json = ['type' => 'message', 'data' => $message];
		
		// Tutaj dodaj kod wysyłający wiadomość na serwer (np. przez gniazda, HTTP itp.)
		
		$output->writeln('Wiadomość wysłana: ' . json_encode($message_json));
	}
}

$application = new Application();
$application->add(new ChatClient());
$application->run();
