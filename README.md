# ServerChannels

Server chat channels plugin for PocketMine-MP

## Category

PocketMine-MP plugins

## Requirements

PocketMine-MP Alpha_1.4 API 1.9.0

## Overview

**ServerChannels** allows you to create customized private or public chat channels.

**EvolSoft Website:** http://www.evolsoft.tk

***This Plugin uses the New API. You can't install it on old versions of PocketMine.***

With ServerChannels you can create private or public chat channels and you can customize them.<br>
You can add a prefix and a suffix and you can edit the format (read documentation)

**Commands:**

<dd><i><b>/serverchannels</b> - ServerChannels commands</i></dd>
<br>
**To-Do:**
<br><br>
*- Bug fix (if bugs will be found)*

## Documentation

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

**Add and configure a channel:**

*Remember that you must have the "serverchannels.channels.<channel>" permission set to true to join the channel*

1. Run the command "/sch new <channel>"<br>
2. Go to "ServerChannels/channels" directory and open the channel config file<br>
This is a channel config file:
```yaml
---
#Channel Prefix
prefix: "&7[&bExampleChannel&7]"
#Channel Suffix
suffix: ""
#Channel format
#Available Tags:
# - {MESSAGE}: Show message
# - {PLAYER}: Show player name
# - {PREFIX}: Show prefix
# - {SUFFIX}: Show suffix
# - {TIME}: Show current time
# - {WORLD}: Show world name
format: "{PREFIX} &7{PLAYER}: &f{MESSAGE}"
#If you set this to false, only players joined in this channel can display messages
public: true
...
```

**Configuration (config.yml):**

```yaml
---
#Date\Time format (replaced in {TIME}). For format codes read http://php.net/manual/en/datetime.formats.php
datetime-format: "H:i:s"
#Log channel messages on console
log-on-console: true
...
```

**Commands:**

***/serverchannels*** *- ServerChannels commands (aliases: [serverch, sch])*
***/sch info*** *- Show info about this plugin*
***/sch help*** *- Show help about this plugin*
***/sch reload*** *- Reload the config*
***/sch list*** *- Show the list of all channels*
***/sch join &lt;channel&gt;*** *- Join a channel*
***/sch leave*** *- Leave the current channel*
***/sch new &lt;channel&gt;*** *- Create new channel*
<br>
**Permissions:**
<br><br>
- <dd><i><b>serverchannels.*</b> - ServerChannels permissions.</i></dd>
- <dd><i><b>serverchannels.channels.*</b> - ServerChannels channels permissions.</i></dd>
- <dd><i><b>serverchannels.commands.*</b> - ServerChannels commands permissions.</i></dd>
- <dd><i><b>serverchannels.commands.help</b> - ServerChannels command Help permission.</i></dd>
- <dd><i><b>serverchannels.commands.info</b> - ServerChannels command Info permission.</i></dd>
- <dd><i><b>serverchannels.commands.reload</b> - ServerChannels command Reload permission.</i></dd>
- <dd><i><b>serverchannels.commands.list</b> - ServerChannels command List permission.</i></dd>
- <dd><i><b>serverchannels.commands.join</b> - ServerChannels command Join permission.</i></dd>
- <dd><i><b>serverchannels.commands.leave</b> - ServerChannels command Leave permission.</i></dd>
- <dd><i><b>serverchannels.commands.new</b> - ServerChannels command New permission.</i></dd>
