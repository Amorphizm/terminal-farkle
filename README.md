# terminal-farkle
A terminal version of the dice game farkle built with ruby 2.5
-
**Running the code** - 
- within the code's directory, execute the command ruby program.rb
**Scoring** - 
- 5 = 50pts
- 1 = 100pts
- 3 of a kinds: 3 1's = 1000pts | 3 2's = 200pts | 3 3's = 300pts | 3 4's = 400pts | 3 5's = 500pts | 3 6's = 600pts
- 4 of a kind = 1000pts
- 5 of a kind = 3000pts
- 6 of a kind = 5000pts
- 2 three of a kinds = 1500pts eg. [1,1,1,2,2,2]
- 3 two of a kinds = 2500pts eg. [1,1,2,2,3,3]
- 4 of a kind with a 2 of a kind = 1200pts eg. [5,5,5,5,6,6]
- a straight = 1500pts eg. [1,2,3,4,5,6] 
<a/>
Points to win = 10,000 or more | configured for no less than 1 player and no more than 6 players but that can be changed within the code.
To start the game run the command ruby program.rb in the cloned directory. To add a bot just add the text ".bot" to the end of the player name.
