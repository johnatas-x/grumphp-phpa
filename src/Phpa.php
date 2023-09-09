<?php

declare(strict_types=1);

namespace GrumphpPhpa;

use GrumPHP\Runner\TaskResult;
use GrumPHP\Runner\TaskResultInterface;
use GrumPHP\Task\AbstractExternalTask;
use GrumPHP\Task\Config\ConfigOptionsResolver;
use GrumPHP\Task\Context\ContextInterface;
use GrumPHP\Task\Context\GitPreCommitContext;
use GrumPHP\Task\Context\RunContext;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Phpa task.
 */
final class Phpa extends AbstractExternalTask
{

  public static function getConfigurableOptions(): ConfigOptionsResolver {
    $resolver = new OptionsResolver();

    $resolver->setDefaults([
      'path' => '',
      'exclude' => [],
    ]);

    $resolver->addAllowedTypes('path', ['string']);
    $resolver->addAllowedTypes('exclude', ['array']);

    return ConfigOptionsResolver::fromOptionsResolver($resolver);
  }

  public function canRunInContext(ContextInterface $context): bool
  {
    return $context instanceof GitPreCommitContext || $context instanceof RunContext;
  }

  public function run(ContextInterface $context): TaskResultInterface
  {
    $config = $this->getConfig()->getOptions();

    $path = $config['path'];

    if (empty($path)) {
      return TaskResult::createSkipped($this, $context);
    }

    $arguments = $this->processBuilder->createArgumentsForCommand('phpa');
    $arguments->add($path);
    $arguments->addOptionalCommaSeparatedArgument('--exclude=%s', $config['exclude']);

    $process = $this->processBuilder->buildProcess($arguments);
    $process->run();

    if (!$process->isSuccessful()) {
      return TaskResult::createFailed($this, $context, $this->formatter->format($process));
    }

    return TaskResult::createPassed($this, $context);
  }

}
