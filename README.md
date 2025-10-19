# CAVETRACK

### The Archeology

-  A multi-platform bot that helps users track token prices, 
- sends them immediate notification on either `tg` or `farcaster`

### The plan

- A custom API with php that fetches token data
- Add users to a database and the tokens they're monitoring 
- triggers the tg bot and farcaster bot when tokens get to the target price

### The TG bot

- built with Node Js, always running
- gets notifications when a target is met
- used to collect username, tokens they want to monitor

### The Farcaster Bot

- built with Node Js, always running
- gets notifications when a target is met
- used to collect username, tokens they want to monitor