$(function() {

    //saving

    jQuery('#save').click(function() {
        $.ajax({
            url: '../save.php',
            data: {
                money: player.money,
                exp: player.experience,
                level: player.level ,
                reputation: player.reputation
            },
            type: 'post'
        });
    });

    //hiding buttons on the list
    $('#buttons ul.options').hide();

    //Hide buttons again on second click
    $('.selector').click(function() {
        $(this).parent().find('.options').slideToggle();
    });

    //Clicking on the task button
    $('.options .btn-outline-info').click(function() {
        let thisButton = $(this);

        //check if it has a price
        let price = thisButton.data("price");
        if (price) {
            if (price > player.money) {
                thisButton.parent().find('p').show().delay(3000).fadeOut();
                return;
            } else {
                player.money -= price;
                $('#profile').find('#money_span').html(player.money);
            }
        }

        let options = $(this).closest('.options');
        let buttonParent = options.parent().find('.selector');
        buttonParent.prop('disabled', true);
        options.slideToggle();
        let type = thisButton.data("type");
        let exp = thisButton.data("experience");

        //Temp counting clicks
        player.clickCounter++;
        if (player.clickCounter == 10) {
            unlockAchievementUsingClass('Clicker');
        }

        //waiting animation callback function
        let afterTimePasses = function temp() {
            player.addExperience(exp);
            player.addSkillPoints(exp, type);

            if (thisButton.data("reputation") > 0) {
                player.addReputation(thisButton.data("reputation"));
            }

            let income = thisButton.data("income");
            if (income) {
                player.money += income;
                if(player.money > 200) {unlockAchievementUsingClass('$200');}
                $('#profile').find('#money_span').html(player.money);
            }
        };

        timer(buttonParent.find('div'), $(this).data("time"), afterTimePasses);

    });

    //Display Tooltip on button hover
    $('.options .btn-outline-info').hover(function() {

        $(this).parent().find('span').css("visibility", "visible");
    }, function() {

        $(this).parent().find('span').css("visibility", "hidden");
    });
    //Get Player Name
    $('#welcome').submit(function(event) {

        event.preventDefault();
        player.name = $("#name").val() || "Player";
        $('#profile').find('#playerName').html(player.name);
        $('#welcome').hide("slow");
    });

    //Hide Achievement popUp
    $('#achievements-popUp').submit(function(event) {
        event.preventDefault();
        $('#achievements-popUp').hide("slow");
        let icon = achievements_popUp.find('i');
        achievements_panel.append(icon);
    });

    //Buy item in the shop
    $('#shop').find('i').click(function() {
        let icon = $(this);
        let price = icon.data("price");
        if (price) {
            if (price > player.money) {
                icon.find('a').tooltip("show");
                return;
            }
            player.money -= price;
            $('#profile').find('#money_span').html(player.money);
            icon.hide("slow");
        };
    });
    $('#shop').find('i').mouseleave(function() {
        $(this).find('a').tooltip("hide");
    });
    //End of document selector
});

//button timer
function timer($div, time, result) {
    $div.show().width("100%");
    $div.animate({
        width: 0
    }, time * 1000, function() {
        $div.parent().prop('disabled', false);
        $div.hide();
        result();
    });
}




//--------------------------------------------------------------------------------------------------------------

//Player section
class Player {

    constructor(name) {
        this.name = name || "Player";
        this.experience = 0;
        this.level = 0;
        this.money = 100;
        this.reputation = 0;

        //skills
        this.coding = 0;
        this.art = 0;
        this.music = 0;
        this.design = 0;

        this.clickCounter = 0;
    }

    addExperience(exp) {
        let bar = $("#experience_bar");
        let maxValue = bar.attr('aria-valuemax');
        this.experience += exp;
        if (this.experience >= 100 + this.level * 10 + this.level * this.level * 5) {
            this.experience -= 100 + this.level * 10 + this.level * this.level * 5;
            this.level++;
            $('#level_span').html(this.level);
            //TODO levelUP animation
            maxValue = 100 + this.level * 10 + this.level * this.level * 5;
            bar.attr('aria-valuemax', maxValue);
            if (this.level == 2) {
                unlockAchievementUsingClass('GitHub');
            } else if (this.level == 3) {
                unlockAchievementUsingClass('Linux');
            }
        }
        let width = this.experience / maxValue * 100;
        bar.css('width', width + '%').attr('aria-valuenow', this.experience);
    };

    addReputation(amount) {
        this.reputation += amount;
        $('#profile').find('#reputation_span').html(this.reputation);
    }

    addSkillPoints(amount, type) {
        let bar = $("#" + type);
        let maxValueForSkill = 100;
        let unlocked = false;
        switch (type) {
            case "coding":
                this.coding += amount;

                if (this.coding >= maxValueForSkill) {
                    this.coding -= maxValueForSkill;
                    this.addExperience(15);

                }

                let width = this.coding / maxValueForSkill * 100;

                bar.css('width', width + '%').attr('aria-valuenow', this.coding);
                break;

            case "art":
                this.art += amount;
                if (this.art >= maxValueForSkill) {
                    this.art -= maxValueForSkill;
                    this.addExperience(15);
                    if (!unlocked) {
                        unlockAchievementUsingClass('Photoshop');
                        unlocked = true;
                    }
                }
                let width2 = this.art / maxValueForSkill * 100;
                bar.css('width', width2 + '%').attr('aria-valuenow', this.art);
                break;

            case "music":
                this.music += amount;
                if (this.music >= maxValueForSkill) {
                    this.music -= maxValueForSkill;
                    this.addExperience(15);
                }
                let width3 = this.music / maxValueForSkill * 100;
                bar.css('width', width3 + '%').attr('aria-valuenow', this.music);
                break;

            case "design":
                this.design += amount;
                if (this.design >= maxValueForSkill) {
                    this.design -= maxValueForSkill;
                    this.addExperience(15);
                }
                let width4 = this.design / maxValueForSkill * 100;
                bar.css('width', width4 + '%').attr('aria-valuenow', this.design);
                break;
            default:

                break;
        }
    };

    set newName(newName) {
        this.name = newName;
    }
}

let player = new Player();
//End of Player Section

//--------------------------------------------------------------------------------------------------------------

//Achievement Section
class Achievement {
  constructor(name, icon, description, reward) {
  		this._name = name;
  		this._icon = icon;
  		this._description = description;
      this._reward = reward;
  	}
    get name() { return this._name; }
  	get icon() { return this._icon; }
  	get description() { return this._description; }
	  get reward() { return this._reward; }

    reward(){
      this._reward();
    }

}

let achievementsFromFunction = createDatabaseMap();
function createDatabaseMap(){
  let achievements = new Map();
  achievements.set('GitHub', new Achievement("GitHub","github","You've discovered source control software. Repotation incrises",function(){ return player.addReputation(57)} ));
  achievements.set('Linux', new Achievement("Linux","linux","You've discovered new operating system. Repotation incrises",function(){ return player.addReputation(17)} ));
  achievements.set('200$', new Achievement("First Money","money","You've saved some money. Repotation incrises ",function(){ return player.addReputation(107)} ));
  achievements.set('Clicker', new Achievement("Clicker","bolt","You are in the flow. Repotation incrises ",function(){ return player.addReputation(107)} ));
  achievements.set('Photoshop', new Achievement("Photoshop","photo","You've started earning money. Repotation incrises ",function(){ return player.addReputation(107)} ));
  return achievements;
}

let achievements_popUp = $('#achievements-popUp');
let achievements_panel = $('#achievements');

function unlockAchievementUsingClass(name){
  let achievementObject = achievementsFromFunction.get(name);
  achievements_popUp.find('h2').html(achievementObject.name);
  achievements_popUp.find('p').html(achievementObject.description);
  let icon = $('<i />', {
      "class": "fa fa-" + achievementObject.icon
  });
  console.log(icon);
  achievements_popUp.prepend(icon);
  achievements_popUp.show();
  achievementObject.reward();
}
//End of Achievement Section


//--------------------------------------------------------------------------------------------------------------
