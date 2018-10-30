new Vue
({
    el: 'body',

    props : ['rpp', 'refresh', 'slide'],

    data: {
        events: [],
        currentPage: 1,
        totalPages: 1,
        prevEvents: "",
        scroll: null,
        noEvent: false,
    },

    directives : {

        height: function(rowHeight) {
            this.el.setAttribute("style","height:" + rowHeight + 'px;max-height:' + rowHeight + 'px;min-height:' + rowHeight + 'px');
        },

        direction : function(directionID)
        {
            var direction = "/cm/img/directional/";

            switch(parseInt(directionID))
            {
                case 1 : direction += 'left.svg?v=3';break;
                case 2 : direction += 'right.svg'; break;
                case 3 : direction += 'up.svg'; break;
                case 4 : direction += 'down.svg'; break;
                case 5 : direction += 'esc_left_up.svg'; break;
                case 6 : direction += 'esc_right_down.svg'; break;
                case 7 : direction += 'esc_right_up.svg'; break;
                case 8 : direction += 'esc_left_down.svg'; break;
                case 9 : direction += 'stairs_left_up.svg'; break;
                case 10: direction += 'stairs_left_down.svg'; break;
                case 11: direction += 'stairs_right_up.svg'; break;
                case 12: direction += 'stairs_right_down.svg'; break;
    			case 15: direction += 'up_left.svg'; break;
    			case 16: direction += 'up_right.svg'; break;
    			case 17: direction += 'down_left.svg'; break;
    			case 18: direction += 'down_right.svg'; break;
                case 19: direction += 'u_turn.svg'; break;
    			case 20: direction += 'go_around.svg'; break;
            }

            this.el.src = direction;
        }
    },

    computed : {

        rowHeight : function()
        {
             return $('#eventsContainer').height() / this.rpp;
        },
    },

    methods : {

        scrollEvent : function()
        {
            var self = this;

            if(self.currentPage == self.totalPages)
            {
                $("#events").css('top', '0px');

                self.currentPage = 1;

                return;
            }

            $( "#events" ).animate
            ({
                top: "-=" + $('#eventsContainer').height() + 'px'


            },1000,
            function()
            {
                self.currentPage++;
            });
        },

        fetchData : function()
        {
            var self = this;
             // Replacing rpp in config direction,
             // Auto detect how many rows per page for directional display
             this.rpp = (Math.floor(($('#eventsContainer').height())/121));
               document.getElementById("offline").textContent = "";


            this.$http({url: '/cm/client/data/directional', method: 'GET'}).then(function (response)
            {
                if(JSON.stringify(response.data) === this.prevEvents)
                {
                    setTimeout(this.fetchData, this.refresh * 1000);
                    return;
                }

                if(response.data.length > 0)
                {

                    this.noEvent = false;



                    this.$set('events', response.data);

                    clearInterval(this.scroll);


                    this.totalPages = Math.ceil(response.data.length / this.rpp);

                    this.prevEvents = JSON.stringify(this.events);

                    setTimeout(this.fetchData, this.refresh * 1000);

                    this.$nextTick(function () {
                        svgColor($('img.direction').css('color'));
                    });

                    if(response.data.length > this.rpp)
                    // if(response.data.length > 4)
                        this.scroll = setInterval(self.scrollEvent, this.slide * 1000);
                }
                else
                {
                        this.noEvent = true;
                        setTimeout(this.fetchData, this.refresh * 1000);


                }

            },function (response)
            {
                console.error("Could not get events", response);
                setTimeout(this.fetchData, this.refresh * 1000);
                document.getElementById("offline").textContent = ".";
            });
        }
    },

    ready: function()
    {
        this.fetchData();

        window.onresize = function(event) {
            window.location.reload();
        };
    }
})


function svgColor(color)
{
    //Replace all SVG images with inline SVG
    $('img.svg').each(function()
    {
        var $img = $(this);
        var imgID = $img.attr('id');
        var imgClass = $img.attr('class');
        var imgURL = $img.attr('src');

        $.get(imgURL, function(data)
        {
            // Get the SVG tag, ignore the rest
            var $svg = jQuery(data).find('svg');

            // Add replaced image's ID to the new SVG
            if(typeof imgID !== 'undefined') {
                $svg = $svg.attr('id', imgID);
            }
            // Add replaced image's classes to the new SVG
            if(typeof imgClass !== 'undefined') {
                $svg = $svg.attr('class', imgClass+' replaced-svg');
            }

            // Remove any invalid XML tags as per http://validator.w3.org
            $svg = $svg.removeAttr('xmlns:a');

            // Replace image with new SVG
            $img.replaceWith($svg);

            //Fill with the config color
            $svg.css('fill',color);
            $svg.find('path').css('fill',color);
        });
    });
}
