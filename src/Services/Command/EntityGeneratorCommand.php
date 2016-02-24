<?php

namespace Services\Command;

use Silex\Application;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/* Callable by using app/console startSilex:generate-entities */

class EntityGeneratorCommand extends \Knp\Command\Command
{

    protected $classes = [];
    protected $app;
    protected function configure() {
        $this
            ->setName("entities:generate")
            ->setDescription("Generate entities starting from database tables");
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $this->app = $this->getSilexApplication();

        /* spostare nella db e poi pushare su startsilex */
        $dbData = $this->app["container"]->get("database")->getTables();

        foreach($dbData as $table) {
            if(empty($table["name"])) continue;
            $className = $this->camelize($table["name"],true);

            $columns = $this->app["container"]->get("database")->getColumns($table["name"]);

            $attributes = [];
            $methods = [];

            foreach($columns as $column) {
                array_push($attributes,$column["name"]);
                array_push($methods,$this->addMethod($column));
            }
            $classArray = [
                "name" => $className,
                "attributes" => $attributes,
                "methods" => $methods
            ];


            if(file_exists(getcwd()."/src/Entity/".$className.".php")) {

                $helper = $this->getHelper('question');
                $question = new ConfirmationQuestion("<fg=yellow;options=bold>(?)</>  {$className} already exist: overwrite? (y/n)", false);


                if ($helper->ask($input, $output, $question)!="y") {
                    continue;
                }

            }

            $output->write("\t<fg=white;>\tCreating {$className}.php </>");
            $this->createEntityFile($classArray);
            $output->write("<fg=green;options=bold>(done)</>\n");

        }

    }


    protected function createEntityFile($classArray) {
        $file = $this->app["twig"]->render("entity-generator/entity.html.twig", array("class" => $classArray));
        file_put_contents(getcwd()."/src/Entity/{$classArray["name"]}.php", $file);
    }

    protected function addMethod($column) {
        return array(
            "name" => $this->camelize($column["name"],true),
            "attribute" => $column["name"],
        );
    }

    protected function camelize( $string, $first_char_caps = false)
    {
        if( $first_char_caps == true ) {
            $string[0] = strtoupper($string[0]);
        }

        $func = create_function('$c', 'return strtoupper($c[1]);');
        return preg_replace_callback('/_([a-z])/', $func, $string);
    }
}