<script setup>
  import { computed, getCurrentInstance, onMounted, ref, watch } from 'vue'
  import { Chart, ArcElement, PieController, PolarAreaController, RadialLinearScale, Tooltip } from 'chart.js'
  import { useDataset } from '../../composables/useDataset'

  Chart.register([ArcElement, PieController, PolarAreaController, RadialLinearScale, Tooltip])

  const props = defineProps({
    value: {
      type: String,
      default: 'page',
    },
    preview: {
      type: Object,
      default: () => ({
        highlight: null,
        target: 'page',
      }),
    },
    page: {
      type: Object,
      required: true,
    },
    pages: {
      type: Array,
      required: true,
      default: () => [],
    },
    files: {
      type: Array,
      required: true,
      default: () => [],
    },
    current: {
      type: Number,
      default: 0,
    },
    limit: {
      type: Number,
      default: 5,
    },
  })

  const vue = getCurrentInstance()?.proxy
  const { chunks } = useDataset(props)
  const canvas = ref()
  const chart = ref()
  const labels = computed(() => [...chunks.value.map((item) => item.label)])
  const data = computed(() => [...chunks.value.map((item) => item.size)])
  const background = computed(() => [...chunks.value.map((item) => (props.preview.highlight && props.preview.highlight !== item.id ? item.hover : item.color))])

  onMounted(() => draw())
  watch([props.preview, () => props.page, () => props.current, props.preview.highlight], () => update())

  function draw() {
    chart.value = new Chart(canvas.value, {
      type: 'pie',
      options: {
        responsive: true,
        layout: {
          padding: 15,
        },
        interaction: {
          mode: 'point',
        },
        plugins: {
          tooltip: {
            callbacks: {
              label: (context) => ` ${chunks.value[context.dataIndex].nice}`,
              footer: (context) => {
                const type = chunks.value[context[0].dataIndex]?.type || null
                return type ? vue.$helper.string.ucfirst(type) : null
              },
            },
          },
        },
      },
      data: {
        labels: labels.value,
        datasets: [
          {
            label: 'Dataset',
            data: data.value,
            backgroundColor: background.value,
            borderWidth: 1,
            hoverBorderWidth: 1,
          },
        ],
      },
    })
  }

  function update() {
    chart.value.data.labels = labels.value
    chart.value.data.datasets[0].data = data.value
    chart.value.data.datasets[0].backgroundColor = background.value
    chart.value.update()
  }
</script>

<template>
  <div class="k-x-ray-chart">
    <canvas ref="canvas"></canvas>
  </div>
</template>

<style scoped>
  .k-x-ray-chart {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 300px;
    max-height: 300px;
    width: 100%;
  }
</style>
