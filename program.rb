require_relative 'player.rb'
require_relative 'bot.rb'
require_relative 'brains.rb'
require 'io/console'

num_players_valid = false 
while num_players_valid == false 
    print "Enter the amount of players: "
    number_of_players = gets.chomp.to_i
    if number_of_players <= 0 or number_of_players > 6
        puts "Invalid number of players - must be greater than 1 and less than 7."
    else 
        player_array = create_players(number_of_players)
        num_players_valid = true
    end 
end 

puts "-----------------Welcome-to-Farkle!-----------------"

#begin the game loop
winner = false 
while winner == false 
    player_array.each do |player|
        #is the player a bot or not
        if player.class == Player 
            player.take_turn(player_array) 
            if player.score >= 10000
                puts "GAME OVER - #{player.name} won with #{player.score} points!"
                winner = true
                break
            end
        else 
            player.take_turn()
            if player.score >= 10000
                puts "GAME OVER - #{player.name} won with #{player.score} points!"
                winner = true
                break
            end
        end 
    end 
end 