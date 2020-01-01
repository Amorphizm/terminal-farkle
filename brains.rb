require_relative 'player.rb'
###################################################################################################################################################
def combo_check(array) #check for any combos within the roll and send the data to score_dice()
    hash = Hash.new(0)
    returnData = Array.new  
    array.each {|number| hash[number] += 1} 
    if array.size == 6
        if hash.has_value?(2) and hash.has_value?(4)
            puts "You rolled a 4 pair with a 2 pair! Adding 1200 points to the bank."
            returnData[0] = 0
            returnData[1] = 1200 
        elsif hash.has_value?(2) || hash.has_value?(3)
            count = 0
            hash.each do |key, value|
                if value == 2 
                    count += 1 
                end 
            end 
            if count == 3 
                puts "You rolled three pairs of 2 of a kind! Adding 1500 points to the bank."
                returnData[0] = 0
                returnData[1] = 1500
            else 
                count = 0
                hash.each do |key, value|
                    if value == 3 
                        count += 1 
                    end 
                end 
                if count == 2 
                    puts "You rolled two pairs of 3! Adding 2500 points to the bank."
                    returnData[0] = 0
                    returnData[1] = 2500 
                elsif count == 1 
                    #one pair of 3 
                    hash.each do |key, value|
                        if value < 3
                            hash.delete(key)
                        else 
                            if key == 1 
                                returnData[1] = 1000
                            elsif key == 2 
                                returnData[1] = 200
                            elsif key == 3
                                returnData[1] = 300
                            elsif key == 4 
                                returnData[1] = 400 
                            elsif key == 5 
                                returnData[1] = 500 
                            else  
                                returnData[1] = 600
                            end 
                        end 
                    end
                    returnData[0] = hash.keys[0]
                else 
                    #no combo was rolled 
                    returnData[0] = 0 
                    returnData[1] = 0
                end  
            end
        elsif hash.has_value?(4) #4 of a kind
            puts "You rolled 4 of a kind! Adding 1000 points to the bank."
            returnData[0] = hash.select {|key, value| value == 4}
            returnData[1] = 1000
        elsif hash.has_value?(5) #5 of a kind
            puts "You rolled 5 of a kind! Adding 3000 points to the bank."
            returnData[0] = hash.select {|key, value| value == 5}
            returnData[1] = 3000
        elsif hash.has_value?(6) #6 of a kind
            puts "You rolled 6 of a kind! Adding 5000 points to the bank."
            returnData[0] = 0
            returnData[1]= 5000
        else 
            puts "You rolled a straight! Adding 1500 points to the bank."
            returnData[0] = 0
            returnData[1] = 1500
        end
    else
        if hash.has_value?(3)
            #one pair of 3 
            hash.each do |key, value|
                if value < 3
                    hash.delete(key)
                else 
                    if key == 1 
                        returnData[1] = 1000
                    elsif key == 2 
                        returnData[1] = 200
                    elsif key == 3
                        returnData[1] = 300
                    elsif key == 4 
                        returnData[1] = 400 
                    elsif key == 5 
                        returnData[1] = 500 
                    else  
                        returnData[1] = 600
                    end 
                end 
            end
            returnData[0] = hash.keys[0]
        elsif hash.has_value?(4) #4 of a kind
            puts "You rolled 4 of a kind! Adding 1000 points to the bank."
            returnData[0] = hash.select {|key, value| value == 4}
            returnData[1] = 1000
        elsif hash.has_value?(5) #5 of a kind
            puts "You rolled 5 of a kind! Adding 3000 points to the bank."
            returnData[0] = hash.select {|key, value| value == 5}
            returnData[1] = 3000
        else
            #no combo was rolled 
            returnData[0] = 0 
            returnData[1] = 0
        end 
    end  
    return returnData 
end 
###########################################################################################################################################
def scoreDice(data, bank, dice_set, farkle_flag) #determine the user's score from the roll and give them options based on that roll
    bank_start = bank
    if data[0] == 0 and data[1] == 0 #no combos but maybe some 1's or 5's
        sum = 0
        ones_fives = Hash.new(0)
        dice_set.each do |die|
            if die == 1 or die == 5
                ones_fives[die] += 1 
            end
        end
        ones_fives.each do |key, value|
            sum += value
        end
        if ones_fives.empty? 
            puts "Sorry - you farkled! Adding 1 to your farkle count and clearing the bank."
            farkle_flag = true
        elsif sum == 1
            puts "Auto banking the single valid die."
            ones_fives.each do |key, value|
                if key == 1
                    bank += 100 * value 
                else
                    bank += 50 * value 
                end 
                dice_set.delete(key)
            end 
        else #there are at least 2 bankable die within the roll, user has to bank at least one of them to continue
            valid = false 
            while valid == false 
                puts "Remaining die - #{dice_set}"
                print "You have #{sum} die you can bank, would you like to bank (a)all or (o)one/some of them?: " 
                answer = gets.chomp.to_s
                if answer.downcase.eql?('a') or answer.downcase.eql?('o')
                    valid = true 
                else 
                    puts 'Invalid input - try again.' 
                end 
            end 
            if answer.eql?('a')
                ones_fives.each do |key, value|
                    if key == 1
                        bank += 100 * value 
                    else
                        bank += 50 * value 
                    end 
                    dice_set.delete(key)
                end
            else 
                puts "Which of the available dice would you like to bank?"
                valid = false 
                while valid == false
                    puts "Remaining die - #{dice_set}"
                    print "Enter the dice's position(s) in the list that you would like to bank: "
                    position = gets.chomp
                    options = position.to_s.each_char.each_slice(1).map{|i| i.join.to_i}
                    options = options.sort.reverse 
                    if options.empty?
                        puts 'Invalid input - try again.'
                    elsif options.length > dice_set.length 
                        puts 'Invalid input - try again.'
                    else
                        some_nums_valid = false 
                        options.each do |i|
                            if dice_set[i-1] == 1 or dice_set[i-1] == 5 
                                some_nums_valid = true
                            end 
                        end 
                        if some_nums_valid == true
                            valid = true 
                        else 
                            puts "Invalid input - none of the dice position(s) entered contains a 1 or 5." 
                        end 
                    end
                end 
                options.each do |i|
                    if dice_set[i-1] == 1 
                        bank += 100 
                        dice_set.delete_at(i-1) 
                    elsif dice_set[i-1] == 5 
                        bank += 50
                        dice_set.delete_at(i-1)
                    else 
                        puts "Invalid input, the die in position #{i} is not a 1 or a 5."
                    end 
                end 
            end 
        end 
    elsif data[0] == 0 and data[1] != 0 #a combo that used all of the die
        bank += data[1]
        dice_set.clear
    elsif data[0].class == Hash #a 4 combo or 5 combo 
        if data[0].key(4) != nil
            dice_set.delete(data[0].key(4))
            bank += data[1] 
        else
            dice_set.delete(data[0].key(5))
            bank += data[1]
        end
        sum = 0
        ones_fives = Hash.new(0)
        dice_set.each do |die|
            if die == 1 or die == 5
                ones_fives[die] += 1 
            end
        end
        ones_fives.each do |key, value|
            sum += value
        end
        if !ones_fives.empty? 
            valid = false 
            while valid == false 
                puts "Remaining die - #{dice_set}"
                print "You have #{sum} remainig die you can bank, would you like to bank (a)all, (s)some or (n)none of them?: " 
                answer = gets.chomp.to_s
                if answer.downcase.eql?('a') or answer.downcase.eql?('s') or answer.downcase.eql?('n')
                    valid = true 
                else 
                    puts 'Invalid input - try again.' 
                end 
            end 
            if answer.eql?('a')
                ones_fives.each do |key, value|
                    if key == 1
                        bank += 100 * value 
                    else
                        bank += 50 * value 
                    end 
                    dice_set.delete(key)
                end 
            elsif answer.eql?('n')
                puts "Banking none of the remaining die - amount of rollable die left = #{dice_set.size}"
            else  
                puts "Which of the available dice would you like to bank?"
                valid = false 
                while valid == false
                    puts "Remaining die - #{dice_set}"
                    print "Enter the dice's position(s) in the list that you would like to bank: "
                    position = gets.chomp
                    options = position.to_s.each_char.each_slice(1).map{|i| i.join.to_i}
                    options = options.sort.reverse 
                    if options.empty?
                        puts 'Invalid input - try again.'
                    elsif options.length > dice_set.length 
                        puts 'Invalid input - try again.'
                    else
                        some_nums_valid = false 
                        options.each do |i|
                            if dice_set[i-1] == 1 or dice_set[i-1] == 5 
                                some_nums_valid = true
                            end 
                        end 
                        if some_nums_valid == true
                            valid = true 
                        else 
                            puts "Invalid input - none of the dice position(s) entered contains a 1 or 5." 
                        end 
                    end
                end 
                options.each do |i|
                    if dice_set[i-1] == 1 
                        bank += 100 
                        dice_set.delete_at(i-1) 
                    elsif dice_set[i-1] == 5 
                        bank += 50
                        dice_set.delete_at(i-1)
                    else 
                        puts "Invalid input, the die in position #{i} is not a 1 or a 5."
                    end 
                end  
            end 
        else 
            puts 'There are no remaining scorable die but you can roll the remaining 3'
        end
    else #a three combo
        bank += data[1]
        one_num = data[1].to_s.each_char.each_slice(1).map{|i| i.join.to_i}
        one_num.delete(0)
        puts "You rolled a 3 combo of die faces #{one_num[0]} which is #{data[1]} points."
        delete_list = [one_num[0], one_num[0], one_num[0]]
        delete_list.each do |del|
            dice_set.delete_at(dice_set.index(del))
        end
        sum = 0
        ones_fives = Hash.new(0)
        dice_set.each do |die|
            if die == 1 or die == 5
                ones_fives[die] += 1 
            end
        end
        ones_fives.each do |key, value|
            sum += value
        end
        if !ones_fives.empty? 
            valid = false 
            while valid == false 
                puts "Remaining die - #{dice_set}"
                print "You have #{sum} remainig die you can bank, would you like to bank (a)all, (s)some or (n)none of them?: " 
                answer = gets.chomp.to_s
                if answer.downcase.eql?('a') or answer.downcase.eql?('s') or answer.downcase.eql?('n')
                    valid = true 
                else 
                    puts 'Invalid input - try again.' 
                end 
            end 
            if answer.eql?('a')
                ones_fives.each do |key, value|
                    if key == 1
                        bank += 100 * value 
                    else
                        bank += 50 * value 
                    end 
                    dice_set.delete(key)
                end 
            elsif answer.eql?('n')
                puts "Banking none of the remaining die - amount of rollable die left = #{dice_set.size}"
            else  
                puts "Which of the available dice would you like to bank?"
                valid = false 
                while valid == false
                    puts "Remaining die - #{dice_set}"
                    print "Enter the dice's position(s) in the list that you would like to bank: "
                    position = gets.chomp
                    options = position.to_s.each_char.each_slice(1).map{|i| i.join.to_i}
                    options = options.sort.reverse 
                    if options.empty?
                        puts 'Invalid input - try again.'
                    elsif options.length > dice_set.length 
                        puts 'Invalid input - try again.'
                    else
                        some_nums_valid = false 
                        options.each do |i|
                            if dice_set[i-1] == 1 or dice_set[i-1] == 5 
                                some_nums_valid = true
                            end 
                        end 
                        if some_nums_valid == true
                            valid = true 
                        else 
                            puts "Invalid input - none of the dice position(s) entered contains a 1 or 5." 
                        end 
                    end
                end 
                options.each do |i|
                    if dice_set[i-1] == 1 
                        bank += 100 
                        dice_set.delete_at(i-1) 
                    elsif dice_set[i-1] == 5 
                        bank += 50
                        dice_set.delete_at(i-1)
                    else 
                        puts "Invalid input, the die in position #{i} is not a 1 or a 5."
                    end 
                end 
            end 
        else 
            puts 'There are no remaining scorable die but you can roll the remaining 3.'
        end
    end 
    difference = bank_start - bank
    if difference < 0 
        difference = difference * -1 
    end 
    puts "You banked #{difference} points."
    player_data = [dice_set, bank, farkle_flag]
    return player_data
end 
###################################################################################################################################################
def create_set(num)
    return Array.new(num) {rand(1..6)}
end 
###################################################################################################################################################
def create_players(num)
    player_array = Array.new
    count = 1
    while count <= num
        print "What is player number #{count}'s name?: "
        name = gets.chomp
        player = Player.new(name, 0)
        player_array << player
        count += 1 
    end 
    return player_array
end    
###################################################################################################################################################
def show_scores(array, current_player)
    array.each do |player|
        print "#{player.name}'s score: #{player.score} | "
    end 
end 