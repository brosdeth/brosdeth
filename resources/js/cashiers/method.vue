<template>
  <span>
    <span v-if="method == 1"> Open Cash drawer </span>
    <span v-if="method == 2"> Cash Sale </span>
    <span v-if="method == 3"> Other Method </span>
    <span v-if="method == 4"> Manaul </span>
  </span>
</template>
<script>
export default {
  props: {
    method: '',
  },
};
</script>
