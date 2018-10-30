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

            self.$http({url: '/cm/client/data/allrooms/192.168.0.48', method: 'GET'}).then(function (response)
            {
                if(self.prevEvent == JSON.stringify(response.data))
                {
                    console.log('Same data... returning.');

                    setTimeout(self.fetchData, self.refresh * 1000);
                    return;

                }

                if(response.data)
                {
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
                {
                    var eventStyle = document.getElementById('eventStyle');

                    if(eventStyle) eventStyle.remove();

                    self.noEvent = true;
                }

                setTimeout(self.fetchData, self.refresh * 1000);

            },function (response)
            {
                // 'true' makes it not resilient to failure
                /*this.noEvent = true;*/
                // Status code 410 = Gone which is returned by the server if 'no event found' i.e. room is now free.
                //otherwise.. if the SERVER has died/crashed,  we must keep showing the old event data
                if (response.status === 410){
                    this.noEvent = true;
                }
                else{
                // FIXEd - false keeps data on screen
                this.noEvent = false;
                }

                this.noEventRoom = response.data.room;

                setTimeout(this.fetchData, this.refresh * 1000);

                console.error("Could not get event", response);
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
