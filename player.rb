class Player 
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
end 