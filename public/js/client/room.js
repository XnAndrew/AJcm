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

    props: ['refresh'],

    data:
    {
        event: {},
        prevEvent: "",
        noEvent: false,
        noEventRoom: ""
    },

    computed: {
        logo: function (){
            return "data:image/png;base64," + this.event.sch_logo;
        }
    },

    methods :
    {
        fetchData : function()
        {
            var self = this;

            self.$http({url: '/cm/client/data/room', method: 'GET'}).then(function (response)
            {
                if(self.prevEvent == JSON.stringify(response.data))
                {
                    console.log('Same data... returning.');

                    setTimeout(self.fetchData, self.refresh * 1000);
                    return;

                }

                if(response.data)
                {
                    document.getElementById("offline").textContent = "";
                    self.noEvent = false;

                    self.$set('event', response.data);

                    // SOC Screen Bullshit, need to change dom to detect change

                    var roomEl = document.getElementById('room');

                    if(roomEl)
                        roomEl.innerHTML = self.event.rom_name;


                    self.injectCSS(response.data.sch_style);

                    self.prevEvent = JSON.stringify(this.event);
                }
                else
                {   //This is what I am MEANT to get when we do't have any events... but instead we get a 410 error.
                    //so as far as I can tell.... this code never runs.
                    // document.getElementById("offline").textContent = this.noEventRoom+"NoResponse - scream if you see this." ;
                    var eventStyle = document.getElementById('eventStyle');

                    if(eventStyle) eventStyle.remove();

                    self.noEvent = true;
                    //n.b. cant set noeventroom=response.data.room here, because.... if we get here, there is no response data! , so nothing to use to set it to.
                }

                setTimeout(self.fetchData, self.refresh * 1000);

            },function (response)
            {
                // 'true' makes it not resilient to failure
                /*this.noEvent = true;*/
                // Status code 410 = Gone which is returned by the server if 'no event found' i.e. room is now free.
                //otherwise.. if the SERVER has died/crashed,  we must keep showing the old event data
                if (response.status === 410){ // 'error' 410 means NO DATA returned i.e. YOU HAVE NO EVENTS to display
                    this.noEvent = true;    //So set the 'NoEventrs to display' Flag'
                    document.getElementById("offline").textContent = "";
                    this.noEventRoom = response.data.room;  //luckily... 410 error does return some response data, inc room name.
                }
                else{   //there was 'some kind of network/other error- we just (probably) didn't GET any data, for some other reason.
                console.error("Could not get event", response); //network screwed
                // Don't do ANYTHING... just keep disp-laying as/what you had, untl network works again
                document.getElementById("offline").textContent = ".";
                }
                setTimeout(this.fetchData, this.refresh * 1000);    //make sure we try again!
            });
        },

        injectCSS : function(file)
        {
            var sheet = document.getElementById("eventStyle");

            if(sheet) sheet.remove();

            var link = document.createElement("link");
            link.href = "/cm/css/client/templates/" + file + ".css" + "?v=" + new Date().getTime();
            link.type = "text/css";
            link.rel = "stylesheet";
            link.id = "eventStyle";
            document.getElementsByTagName("head")[0].appendChild(link);
        }
    },

    ready: function()
    {
        this.fetchData();
    }
})
