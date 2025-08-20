<script setup>
  import { useDataset } from '../../composables/useDataset'
  import { computed, ref } from 'vue'
  import Icon from '../Icon/Item.vue'

  const emit = defineEmits(['input', 'paginate'])
  const props = defineProps({
    value: {
      type: String,
      default: 'all',
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

  const showSearch = ref(false)
  const dropdown = ref()
  const { chunks, length, options, search } = useDataset(props)
  const buttons = computed(() => [
    {
      icon: 'filter',
      text: window.panel.$t('beebmx.x-ray.filter'),
      click: () => dropdown.value.toggle(),
    },
    { icon: showSearch.value ? 'cancel-small' : 'search', text: window.panel.$t('search'), click: () => onSearchToggle() },
  ])

  const filter = computed(() => {
    return options.value
      .map((option) => ({
        ...option,
        click() {
          return emit('input', option.type)
        },
      }))
      .filter((option) => !['page'].includes(option.type))
  })

  function onSearchToggle() {
    showSearch.value = !showSearch.value
    search.value = null
  }

  function paginate(pagination) {
    emit('paginate', pagination.page)
  }

  function optionsBy(item) {
    return [
      {
        text: window.panel.$t('beebmx.x-ray.inspect'),
        icon: 'x-ray-icon',
        link: `x-ray/${item.id}`,
        disabled: item.type !== 'page',
      },

      {
        text: window.panel.$t('beebmx.x-ray.area.view'),
        icon: 'x-ray-eye',
        link: item.panel,
      },
    ]
  }
</script>

<template>
  <div class="k-x-ray-area-resource">
    <k-section class="k-x-ray-area-resource-header" :label="$t('beebmx.x-ray.resources')" :buttons="buttons">
      <k-dropdown-content ref="dropdown" :options="filter" />
      <k-input v-if="showSearch" :autofocus="true" icon="search" :placeholder="$t('search')" type="search" @input="search = $event" @keydown.native.esc="onSearchToggle" />

      <div class="k-table">
        <table>
          <thead>
            <tr>
              <th data-mobile="true" class="k-x-ray-area-resource-cell-dot"></th>
              <th data-mobile="true">{{ $t('beebmx.x-ray.resource') }}</th>
              <th data-mobile="true">{{ $t('size') }}</th>
              <th>{{ $t('type') }}</th>
              <th data-mobile="true" class="k-table-options-column"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in chunks" :key="index">
              <td data-mobile="true"><Icon :item="item" :status="item?.status" /></td>
              <td class="k-x-ray-area-table-title" data-mobile="true">{{ item.label }}</td>
              <td data-mobile="true">{{ item.nice }}</td>
              <td>{{ $t(`beebmx.x-ray.type.${item.type || 'page'}`) }}</td>
              <td data-mobile="true" class="k-table-options-column">
                <k-options-dropdown :options="optionsBy(item)" />
              </td>
            </tr>
          </tbody>
        </table>
        <k-pagination class="k-table-pagination" :details="true" :limit="limit" :page="current" :total="length" @paginate="paginate" />
      </div>
    </k-section>
  </div>
</template>

<style scoped>
  .k-x-ray-area-resource {
    margin-top: var(--spacing-8);
  }
  .k-x-ray-area-resource .k-table {
    margin-top: var(--spacing-4);
  }
  .k-x-ray-area-resource-cell-dot {
    width: var(--spacing-12) !important;
  }
  .k-x-ray-area-table-title {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
</style>
