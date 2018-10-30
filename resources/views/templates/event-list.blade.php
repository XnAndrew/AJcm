<template id="eventsTemplate">
    <div class="panel-heading">All Active Events <input id="search" type="text" placeholder="Search events..." v-model="search"/></div>

    <table class="table">
        <thead>
        <tr>
            <th @click="sortBy('sch_title')" :class="{ 'active' : sortKey == 'sch_title'}">Title</th>
            <th @click="sortBy('rom_name')" :class="{ 'active' : sortKey == 'rom_name'}">Room</th>
            <th @click="sortBy('sch_start_time')" :class="{ 'active' : sortKey == 'sch_start_time'}">Start</th>
            <th @click="sortBy('sch_end_time')" :class="{ 'active' : sortKey == 'sch_end_time'}">End</td>
            <th>Action</td>
        </tr>
        </thead>
        <tbody>
        <tr v-for="event in events | filterBy search | orderBy sortKey reverse">
            <td>@{{event.sch_title}}</td>
            <td>@{{event.rom_name}}</td>
            <td v-start="event.sch_start_time"></td>
            <td v-end="event.sch_end_time"></td>
            <td><a href="/cm/event/@{{event.sch_id}}/edit" > Edit </a></td>
        </tr>
        <tr v-if="events.length == 0"><td colspan="5">No events to display</td></tr>
    </tbody>
    </table>
</template>
