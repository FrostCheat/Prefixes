# Prefixes Plugin

This plugin is made by FrostCheatMC owner of AquaMC to publish it to the community

This plugin brings its own configurable chat format to display its prefix

This plugin brings support for RankSystem, if you want to show the prefix with RankSystem activate the integration in `config.yml`

This plugin brings MultiLanguage Support

# What`s new v1.2.0

* Fixed bugs with commands, and GUI
* Added MultiLanguage
* Added Update Notifier (Poggit)
* Added ConfigChecker Version
* Show all prefixes in commands (PrefixArguments)
* Show all languages supported in command (/prefix setlanguage)

# Configuration
Don't touch this setting it may break the entire plugin

`config-version: 3`

### Prefix Configuration

`prefix-max-characters-name: 10`

`prefix-max-characters-format: 20`

### Default Language
Supported languages: `en_us`, `es_es`, `fr_fr`, `gr_ge`, `pr_br`, `rs_rs`

`default-language: en_us`

### RankSystem Integration
 If this option is "true" it means that you will use the RankSystem plugin to display the prefix. 

 If this option is "false" it means that you will use the chat format of this Plugin.

 If you change the RankSystem placeholder you must restart the server

 Remember that for it to work in the RankSystem configuration it must be between {}, example {prefix}

`rank-system-chat: false`

`rank-system-prefix-placeholder: "prefix"`

### Plugin Chat Format

`chat-format: "%prefix% &7%name%: &f%message%"`

# Commands

- /prefixes - Shows you a GUI of all the prefixes created
- /prefix set [string: playerName] [string: prefixName] - Set prefix a player
- /prefix remove [string: playerName] - Remove the prefix from a player
- /prefix delete [string: prefixName] - Delete a prefix
- /prefix reload - Reload all plugin
- /prefix save - Save all plugin
- /prefix setlanguage - Sets the default language of plugin
- /prefix create [string: prefixName] [string: format] [string: permission] - Create a prefix
- /prefix help - Show this list of commands

# Permissions

- prefixes.command - default
- prefixes.command.set - op
- prefixes.command.remove - op
- prefixes.command.delete - op
- prefixes.command.reload - op
- prefixes.command.create - op
- prefixes.command.help - op
- prefixes.command.save - op
- prefixes.command.setlanguage - op

# Features

- MultiLanguage Support (NEW)
- Update Notifier (NEW)
- Config Checker (NEW)
- PrefixArguments All prefix shows in commands (NEW)
- Shows you the prefixes in GUI
- Click to place the prefix in the GUI (only if the player has the prefix permission)
- 100% configurable language settings
- RankSystem Support ({prefix} placeholder, or configure in `config.yml`)
- Chat Format
