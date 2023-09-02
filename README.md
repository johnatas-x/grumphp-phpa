# Description

This repository adds a task for GrumPHP that launchs [php-assumptions](https://github.com/rskuipers/php-assumptions).
During a commit check for weak assumption. If an assumption is detected, it won't pass.


# Installation

Install it using composer:

```composer require --dev johnatas-x/grumphp-phpa```


# Usage

1) Add the extension in your grumphp.yml file:
```yaml
extensions:
  - GrumphpPhpa\ExtensionLoader
```

2) Add phpa to the tasks:
```
tasks:
  phpa:
    directory: []
```

- **exclude_dir** (array): Directories to check.