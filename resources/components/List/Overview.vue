<script setup>
  import { useDataset } from '../../composables/useDataset'
  import { computed, onMounted, ref, watch } from 'vue'
  import Icon from '../Icon/Item.vue'
  const emit = defineEmits(['input', 'highlight', 'paginate'])
  const props = defineProps({
    value: {
      type: String,
      default: 'page',
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

  const { options, dataset, chunks } = useDataset(props)
  const dropdown = ref(null)
  const highlighted = ref(null)

  const filter = computed(() => {
    return options.value
      .map((option) => ({
        ...option,
        click: () => emit('input', option.type),
      }))
      .filter((option) => !['all'].includes(option.type))
      .filter((option) => (option.type === 'files' && props.page.files.count > 0) || (option.type === 'pages' && props.page.pages.count > 0) || option.type === 'page')
  })

  const target = computed(() => filter.value.find((option) => option.type === props.value))

  watch([() => props.value, () => props.current], () => {
    highlighted.value = null
  })

  function cellOptions(item) {
    return [
      {
        text: window.panel.$t('beebmx.x-ray.area.view'),
        icon: isSelected(item) ? 'x-ray-eye' : 'x-ray-eye-off',
        click: () => {
          emit('highlight', getHighlight(item))
          highlighted.value = getHighlight(item)
        },
      },
    ]
  }

  function paginate(pagination) {
    emit('paginate', pagination.page)
  }

  function isSelected(item) {
    return item?.id === highlighted.value || highlighted.value === null
  }

  function getHighlight(item) {
    return item?.id === highlighted.value ? null : item.id
  }
</script>

<template>
  <div class="k-x-ray-area-list">
    <div class="x-ray-area-list-heading">
      <k-button-group slot="buttons">
        <k-button :dropdown="true" variant="filled" :icon="target.icon" @click="dropdown.toggle()">
          {{ $helper.string.ucfirst(target.text) }}
        </k-button>
        <k-dropdown-content ref="dropdown" :options="filter" />
      </k-button-group>
    </div>

    <div>
      <div class="k-x-ray-area-list-table k-table">
        <table>
          <thead>
            <tr>
              <th data-mobile="true" class="k-x-ray-area-list-cell-dot"></th>
              <th data-mobile="true">{{ $t('beebmx.x-ray.resource') }}</th>
              <th>{{ $t('size') }}</th>
              <th data-mobile="true" class="k-table-options-column"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in chunks" :key="index">
              <td data-mobile="true"><Icon :item="item" :status="item?.status" /></td>
              <td data-mobile="true">{{ item.label }}</td>
              <td>{{ item.nice }}</td>
              <td data-mobile="true" class="k-table-options-column">
                <k-options-dropdown :options="cellOptions(item)" />
              </td>
            </tr>
          </tbody>
        </table>

        <k-pagination class="k-table-pagination" :details="true" :limit="props.limit" :page="current" :total="dataset.length" @paginate="paginate" />
      </div>
    </div>
  </div>
</template>

<style scoped>
  .k-x-ray-area-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-4);
    width: 100%;
    height: 100%;
    padding: var(--spacing-4);
  }
  .x-ray-area-list-heading {
    display: flex;
    justify-content: end;
    align-items: center;
    width: 100%;
  }
  .k-x-ray-area-list-cell-dot {
    width: var(--spacing-12) !important;
  }
  .k-x-ray-area-list ul {
    width: 100%;
    padding: var(--spacing-4);
  }
</style>
