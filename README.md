![start2](https://cloud.githubusercontent.com/assets/10303538/6315586/9463fa5c-ba06-11e4-8f30-ce7d8219c27d.png)

# ServerChannels

A powerful chat channelling plugin for PocketMine-MP

## Category

PocketMine-MP plugins

## Requirements

PocketMine-MP 1.7dev API 3.0.0-ALPHA7 -> 3.0.0-ALPHA11

## Overview

**ServerChannels** is a powerful chat channelling plugin for PocketMine-MP.
You can create highly customizable chat channels on your MC:PE server thanks to this plugin.

***Features:***

- *Custom format:* customize the channel format with colors and tags
- *Hidden/Unhidden channels:* customize channel visibility (hidden channels messages will be seen only by players who joined that channels, unhidden channels messages will be seen by all players)
- *Custom channels authentication:* create whitelisted channels, protect them with a password or leave them accessible by all players

**EvolSoft Website:** https://www.evolsoft.tk

***This Plugin uses the New API. You can't install it on old versions of PocketMine.***

## Documentation

**Text format (Available on PocketMine console and on MCPE v0.11.0 and later):**

**Colors:**

Black ("&0");<br>
Dark Blue ("&1");<br>
Dark Green ("&2");<br>
Dark Aqua ("&3");<br>
Dark Red ("&4");<br>
Dark Purple ("&5");<br>
Gold ("&6");<br>
Gray ("&7");<br>
Dark Gray ("&8");<br>
Blue ("&9");<br>
Green ("&a");<br>
Aqua ("&b");<br>
Red ("&c");<br>
Light Purple ("&d");<br>
Yellow ("&e");<br>
White ("&f");<br>

**Special:**

Obfuscated ("&k");<br>
Bold ("&l");<br>
Strikethrough ("&m");<br>
Underline ("&n");<br>
Italic ("&o");<br>
Reset ("&r");<br>

**Create and configure a channel:**

*Remember that you must have the "serverchannels.channels.&lt;channel&gt;" permission to join the channel*

1. Run the command "/sch create &lt;channel&gt;"<br>
2. Open "channels.yml" file inside plugin configuration folder and open it<br>
This is a channel config file entry:

```yaml
# Channel name
channel_name:
  # Channel prefix
  prefix: "&7[&bExampleChannel&7]"
  # Channel suffix
  suffix: ""
  # Channel format
  # Available Tags:
  #  - {MESSAGE}: Show message
  #  - {PLAYER}: Show player name
  #  - {PREFIX}: Show prefix
  #  - {SUFFIX}: Show suffix
  #  - {TIME}: Show current time
  #  - {WORLD}: Show world name
  format: "{PREFIX} &7{PLAYER}: &f{MESSAGE}"
  # Channel visiblity (true if you want that channel messages will be seen by players in the channel only, false if you want that channel messages will be seen by all players)
  hidden: false
  # Channel authentication (0 or "none" = no authentication, 1 or "password" = password authentication, 2 or "whitelist" = whitelisted channel)
  auth: "none"
  # Channel password (this field is ignored unless auth is set to 1 or "password")
  password: ""
  # Channel whitelisted players array (this field is ignored unless auth is set to 2 or "whitelist")
  whitelist: []
```

**Configuration (config.yml):**

```yaml
---
# Date\Time format (replaced in {TIME}). For format codes read http://php.net/manual/en/datetime.formats.php
datetime-format: "H:i:s"
# Log channel messages on console
log-on-console: true
# Default channel settings
default-channel:
  # Enable default (join) channel
  enabled: false
  # Default (join) channel
  channel: "users"
...
```

**Commands:**

***/serverchannels*** *- ServerChannels commands (aliases: [serverch, sch])*<br>
***/sch info*** *- Show info about this plugin*<br>
***/sch help*** *- Show help about this plugin*<br>
***/sch reload*** *- Reload the config*<br>
***/sch create &lt;channel&gt;*** *- Create a new channel*<br>
***/sch join &lt;channel&gt; [password]*** *- Join a channel*<br>
***/sch leave*** *- Leave the current channel*<br>
***/sch list*** *- Show the list of all channels*<br>

**Permissions:**

- <dd><i><b>serverchannels.*</b> - ServerChannels permissions.</i></dd>
- <dd><i><b>serverchannels.channels.*</b> - ServerChannels channels permissions.</i></dd>
- <dd><i><b>serverchannels.commands.*</b> - ServerChannels commands permissions.</i></dd>
- <dd><i><b>serverchannels.commands.help</b> - ServerChannels command Help permission.</i></dd>
- <dd><i><b>serverchannels.commands.info</b> - ServerChannels command Info permission.</i></dd>
- <dd><i><b>serverchannels.commands.reload</b> - ServerChannels command Reload permission.</i></dd>
- <dd><i><b>serverchannels.commands.create</b> - ServerChannels command New permission.</i></dd>
- <dd><i><b>serverchannels.commands.join</b> - ServerChannels command Join permission.</i></dd>
- <dd><i><b>serverchannels.commands.leave</b> - ServerChannels command Leave permission.</i></dd>
- <dd><i><b>serverchannels.commands.list</b> - ServerChannels command List permission.</i></dd>

## API

Almost all our plugins have API access to widely extend their features.

To access ServerChannels API:<br>
*1. Define the plugin dependency in plugin.yml (you can check if ServerChannels is installed in different ways):*

```yaml
depend: [ServerChannels]
```

*2. Include ServerChannels API in your plugin code:*

```php
//ServerChannels API
use ServerChannels\ServerChannels;
```

*3. Access the API by doing:*

```php
ServerChannels::getAPI()
```
