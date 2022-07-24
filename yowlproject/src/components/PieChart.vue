<template>
  <div class="legende">
    <div>
      <div class="female"></div>
      <b>Femmes ({{ pieData[0]?.value }} %)</b>
    </div>
    <div>
      <div class="male"></div>
      <b>Hommes ({{ pieData[1]?.value }} %)</b>
    </div>
    <div>
      <div class="others"></div>
      <b>Autres ({{ pieData[2]?.value }} %)</b>
    </div>
  </div>
  <div class="pie-chart" :style="pieStyle"></div>
</template>

<script>
export default {
  props: ["pieData"],

  computed: {
    pieStyle() {
      let sum = 0;

      let styles = this.pieData.map(
        (piePart) => `${piePart.color} 0 ${(sum += piePart.value)}%`
      );

      return { background: "conic-gradient(" + styles.join(",") + ")" };
    },
  },
};
</script>

<style lang="scss" scoped>
.pie-chart {
  width: 300px;
  height: 300px;
  border-radius: 50%;
  border: 2px solid #fefefe;
  animation: show 1s ease-in-out;
}
.female {
  background-color: #0b6487;
  width: 25px;
  height: 17px;
  float: left;
  margin-right: 1rem;
}

.male {
  background-color: #9d1f37;
  width: 25px;
  height: 17px;
  float: left;
  margin-right: 1rem;
}

.others {
  background-color: #f6931c;
  width: 25px;
  height: 17px;
  float: left;
  margin-right: 1rem;
}

.legende {
  display: grid;
  text-align: left;
  margin-left: 4rem;
  margin-top: 4rem;
}
/*
    The rotating animation
  */
@keyframes show {
  0% {
    transform: scale(0) rotate(720deg);
  }
  100% {
    transform: scale(1) rotate(0deg);
  }
}
</style>