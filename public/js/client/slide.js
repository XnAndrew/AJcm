Vue.filter('timeOnly', function (value) {

    if(value) {
        var split = value.split(" ");

        if(split[1]) return split[1].slice(0,-3);
    }

    return value;
});

new Vue
({
    el: 'body',

    props : ['refresh', 'slide'],

    data:
    {
        events: [],
        counter: 1,
        scroll: null,
        prevEvents: "",
        noEvent: false
    },

    computed : {

        height : function()
        {
            return $(window).height();
        }
    },

    directives: {

        height: function(rowHeight) {
            this.el.setAttribute("style","height:" + rowHeight + 'px;max-height:' + rowHeight + 'px;min-height:' + rowHeight + 'px');
        },

        logo: function(logo) {
            this.el.src = "data:image/png;base64," + logo;
        }
    },

    methods :
    {
        logo: function (event){
            return "data:image/png;base64," + event.sch_logo;
        },

        scrollEvent : function()
        {
            var self = this;

            if(self.counter == self.events.length)
            {
                $("#events").css('top', '0px');

                self.counter = 1;

                return;
            }

            $( "#events" ).animate
            ({
                top: "-=" + $(window).height() + 'px'

            },1000,
            function()
            {
                self.counter++;
            });
        },

        fetchData : function()
        {
            var self = this;

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

                    $("#events").stop();

                    clearInterval(this.scroll);

                    $('#events').css('height', 100 * response.data.length + '%');

                    this.$set('events', response.data);

                    this.prevEvents = JSON.stringify(this.events);

                    setTimeout(this.fetchData, this.refresh * 1000);

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
            });
        }
    },

    ready: function()
    {
        this.fetchData();
    }
})
