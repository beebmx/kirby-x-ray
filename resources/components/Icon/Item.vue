<script setup>
  import { computed } from 'vue'

  const props = defineProps({
    item: {
      type: Object,
      required: true,
      default: () => ({
        type: 'page',
      }),
    },
    status: {
      type: String,
      default: null,
    },
  })

  const icon = computed(() => {
    if (props.status === 'unlisted') {
      return 'circle-half'
    }

    if (props.status === 'draft') {
      return 'circle'
    }
    return 'circle-filled'
  })
</script>

<template>
  <k-icon :type="icon" :color="item.color" />
</template>
