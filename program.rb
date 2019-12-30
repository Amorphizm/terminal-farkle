require_relative 'player.rb'
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
        puts "It is now #{player.name}'s turn!"
        farkle_flag = false 
        pass_turn = false 
        dice_set = create_set(6)
        bank_hold = 0
        while pass_turn == false
            bank_temp = 0
            puts "#{player.name}'s score - #{player.score} | bank total - #{bank_hold}" 
            puts "#{player.name} press enter to roll #{dice_set.size} dice."
            STDIN.getch
            puts "You rolled - #{dice_set}"
            combo_data = combo_check(dice_set)
            player_data = scoreDice(combo_data, bank_temp, dice_set, farkle_flag)
            dice_set = player_data[0]
            bank_temp = player_data[1]
            farkle_flag = player_data[2]
            bank_hold += bank_temp
            if farkle_flag == true
                player.update_farkles()
                pass_turn = true
            else 
                print "Do you wish to (r)roll again or (p)pass the turn to add to your score?: "
                answer = gets.chomp
                if answer.eql?('r')
                    if dice_set.empty?
                        puts "Creating a new dice set."
                        dice_set = create_set(6)
                    else 
                        dice_set = create_set(dice_set.size)
                    end 
                else
                    puts "#{player.name} is passing the turn."
                    player.score += bank_hold
                    if player.score >= 10000
                        puts "GAME OVER - #{player.name} won with #{player.score} points!"
                        winner = true
                    else 
                        pass_turn = true
                    end         
                end
            end 
        end 
    end 
end 