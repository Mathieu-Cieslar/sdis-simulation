<script setup>
import { onMounted } from 'vue'
import { useLeafletMap } from '@/composable/useLeafletMap'
import 'leaflet/dist/leaflet.css'

const { initialiseMap,putFeuOnMap } = useLeafletMap()

onMounted(() => {
  initialiseMap()
  putFeuOnMap()

})
</script>

<template>
  <div class="container">
    <div class="card">
      <div class="card-text">
        <div class="row">
          <div class="col-12">
            <div id="mapBaseStation" style="height: 75vh"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.container {
  width: 100%;
  padding: 0 15px;
  margin: 0 auto;
}

.card {
  background: #fff;
  border-radius: 4px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin-bottom: 20px;
}

.card-text {
  padding: 15px;
}

.row {
  display: flex;
  flex-wrap: wrap;
}

.col-12 {
  flex: 0 0 100%;
  max-width: 100%;
}
</style>
