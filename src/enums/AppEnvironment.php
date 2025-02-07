<?php
declare(strict_types=1);

namespace Src\Enums;

enum AppEnvironment: string
{
  case Production = 'production';
  case Local = 'local';
}
