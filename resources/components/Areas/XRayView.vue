<script setup>
  import { computed, reactive, ref, watch } from 'vue'
  import Pie from '../Charts/Pie.vue'
  import Overview from '../List/Overview.vue'
  import Resource from '../List/Resource.vue'

  const props = defineProps({
    cache: Boolean,
    files: Object,
    page: Object,
    pages: Object,
    title: String,
    view: String,
    overview: {
      type: Number,
      default: 5,
    },
    resource: {
      type: Number,
      default: 10,
    },
  })

  const cleaning = ref(false)
  const preview = reactive({
    highlight: null,
    content: props.view,
    page: 1,
  })

  const resource = reactive({
    page: 1,
    content: 'all',
  })

  const cacheIcon = computed(() => (cleaning.value ? 'loader' : 'x-ray-brush'))
  const stats = computed(() => {
    return [
      { value: props.page.nice, label: window.panel.$t('beebmx.x-ray.resource'), info: props.page.title, icon: 'dashboard' },
      { value: `${props.page.pages.count} ${window.panel.$t('pages')}`, label: window.panel.$t('pages'), info: props.page.pages.nice, icon: 'x-ray-pages' },
      { value: `${props.page.files.count} ${window.panel.$t('files')}`, label: window.panel.$t('files'), info: props.page.files.nice, icon: 'x-ray-files' },
    ]
  })

  watch(
    () => preview.content,
    () => {
      preview.page = 1
      preview.highlight = null
    },
  )

  watch(
    () => props.page,
    () => {
      preview.content = props.view
    },
  )

  function paginate(page, type) {
    if (type === 'overview') {
      preview.page = page
      preview.highlight = null
    } else if (type === 'resource') {
      resource.page = page
    }
  }

  function highlightFor(target) {
    preview.highlight = target
  }

  function clearCache() {
    cleaning.value = true
    window.panel.api
      .post('x-ray/cache/clear', {
        id: props.page.id,
      })
      .then(({ success }) => {
        if (success) {
          window.panel.notification.success({
            message: window.panel.$t('beebmx.x-ray.cache.cleared'),
          })

          window.panel.view.refresh()
        } else {
          window.panel.notification.info({
            message: window.panel.$t('beebmx.x-ray.cache.empty'),
          })
        }
      })
      .catch((error) => {
        window.panel.notification.error({
          message: error,
        })
      })
      .finally(() => {
        cleaning.value = false
      })
  }
</script>

<template>
  <k-panel-inside class="k-x-ray-area">
    <k-header>
      {{ props.page.title }}

      <k-button-group slot="buttons">
        <k-button v-if="props.cache" :icon="cacheIcon" :disabled="cleaning" variant="filled" :title="props.page.title" @click="clearCache">{{
          $t('beebmx.x-ray.cache.clear')
        }}</k-button>
      </k-button-group>
    </k-header>

    <div class="k-x-ray-area-stats">
      <k-stats :reports="stats" />
    </div>

    <div>
      <k-grid class="k-x-ray-area-grid" style="gap: var(--spacing-4)">
        <k-box class="k-x-ray-area-box k-x-ray-area-box-centered" align="center">
          <!-- prettier-ignore-->
          <Pie
            v-model="preview.content"
            :current="preview.page"
            :files="props.files"
            :limit="props.overview"
            :page="props.page"
            :pages="props.pages"
            :preview="preview" />
        </k-box>

        <k-box class="k-x-ray-area-box k-x-ray-area-content" align="center">
          <Overview
            v-model="preview.content"
            :current="preview.page"
            :files="props.files"
            :limit="props.limit"
            :page="props.page"
            :pages="props.pages"
            @paginate="paginate($event, 'overview')"
            @highlight="highlightFor"
          />
        </k-box>
      </k-grid>
    </div>

    <Resource
      v-model="resource.content"
      :current="resource.page"
      :files="props.files"
      :limit="props.resource"
      :page="props.page"
      :pages="props.pages"
      @paginate="paginate($event, 'resource')"
    />
  </k-panel-inside>
</template>

<style scoped>
  .k-x-ray-area-stats {
    display: grid;
    width: 100%;
    margin-bottom: var(--spacing-4);
  }
  .k-x-ray-area-grid {
    --columns: 1;
  }
  .k-x-ray-area-box {
    display: flex;
    background: light-dark(var(--color-gray-250), var(--color-gray-850));
    height: 100%;
    border-radius: var(--rounded-md);
    align-items: start;
  }
  .k-x-ray-area-box-centered {
    align-items: center;
  }

  @container (min-width: 30rem) {
    /**/
  }
  @media (min-width: 60rem) {
    .k-x-ray-area-grid {
      --columns: 3;
    }
    .k-x-ray-area-content {
      grid-column: span 2 / span 2;
    }
  }
</style>
