class Bot
    attr_accessor :name, :score, :farkles
    def initialize(name, score)
        @name = name
        @score = score 
        @farkles = 0 
    end 

    def update_farkles()
        @farkles += 1 
        if @farkles == 3 
            puts "OOPS! - you just farkled for the third time. #{@name}'s score and farkle count have been reset to 0."
            @score = 0
            @farkles = 0
        else 
            puts "#{@name} - You have a total of #{@farkles} farkles."
        end 
    end 

    def take_turn(player_array)
        is_bot = true 
        pass_turn = false 
        puts "It is now #{@name}'s turn!"
        #show_scores(player_array)
        bank_hold = 0
        dice_set = create_set(6)
        while pass_turn == false 
            bank_temp = 0
            farkle_flag = false 
            pass_turn = false 
            puts "#{@name} rolled - #{dice_set}"
            combo_data = combo_check(dice_set)
            player_data = scoreDice(combo_data, bank_temp, dice_set, farkle_flag, is_bot)
            dice_set = player_data[0]
            bank_temp = player_data[1]
            farkle_flag = player_data[2]
            bank_hold += bank_temp
            if farkle_flag == true
                self.update_farkles()
                pass_turn = true
            else 
                if @farkles == 0 
                    pass_num = 3
                else
                    pass_num = 4 
                end 
                print "Do you wish to (r)roll again or (p)pass the turn to add to your score?: "
                if dice_set.size == 0 or dice_set.size >= pass_num
                    answer = 'r'
                    puts "#{@name} is rolling again."
                else 
                    answer = 'p'
                end 
                if answer.eql?('r')
                    if dice_set.empty?
                        puts "Creating a new dice set."
                        dice_set = create_set(6)
                    else 
                        dice_set = create_set(dice_set.size)
                    end 
                else
                    puts "#{@name} is passing the turn."
                    @score += bank_hold
                    pass_turn = true         
                end
            end 
        end 
    end 
end