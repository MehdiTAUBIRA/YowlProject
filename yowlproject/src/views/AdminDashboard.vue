<template>

<div class="chart">

<pie-chart :data="[['gender', i], ['gender', i]]"></pie-chart>

<button @click="GenderRepresentation">Gender representation</button>

<column-chart :data="[['title', i], ['title', i], ['title', i]]"></column-chart>

<button @click="updateColumn">Update column</button>

<geo-chart :data="[['country', x], ['country', x], ['countrty', x]]"></geo-chart>

<button @click="updateCountry">Update Country</button>

</div>

</template>

<script>
export default {
    name: 'AdminDashboard',
    

methods: {
    updatePie(){
        this.ChartData ={

        }
    }
}

}



</script>

<style lang='scss'>

</style>
