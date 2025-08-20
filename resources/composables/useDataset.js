import { computed, ref, watch } from 'vue'

export function useDataset(props) {
  const color = {
    files: {
      base: '--color-blue-750',
      hover: '--color-gray-400',
    },
    pages: {
      base: '--color-green-700',
      hover: '--color-gray-400',
    },
    page: {
      base: '--color-green-400',
      hover: '--color-gray-400',
    },
    archive: {
      base: '--color-purple-600',
      hover: '--color-gray-400',
    },
    audio: {
      base: '--color-pink-600',
      hover: '--color-gray-400',
    },
    code: {
      base: '--color-red-600',
      hover: '--color-gray-400',
    },
    document: {
      base: '--color-blue-550',
      hover: '--color-gray-400',
    },
    image: {
      base: '--color-orange-600',
      hover: '--color-gray-400',
    },
    other: {
      base: '--color-gray-800',
      hover: '--color-gray-400',
    },
    video: {
      base: '--color-aqua-800',
      hover: '--color-gray-400',
    },
  }

  const filterBy = ref(props.value)
  const search = ref(null)
  const sort = ref('desc')

  const meta = computed(() => ({
    all: {
      id: props.page.id,
      title: props.page.title,
      label: props.page.label,
      icon: props.page.icon,
    },
    page: {
      id: props.page.id,
      title: props.page.title,
      label: props.page.label,
      icon: props.page.icon,
    },
    pages: {
      id: props.page.pages.id,
      title: props.page.pages.title,
      label: props.page.pages.label,
      icon: props.page.pages.icon,
    },
    files: {
      id: props.page.files.id,
      title: props.page.files.title,
      label: props.page.files.label,
      icon: props.page.files.icon,
    },
  }))

  const options = ref([
    {
      icon: 'dashboard',
      text: window.panel.$t('beebmx.x-ray.resources.all'),
      type: 'all',
    },
    {
      icon: meta.value['page'].icon,
      text: window.panel.$t('page'),
      type: 'page',
    },
    {
      icon: meta.value['pages'].icon,
      text: window.panel.$t('pages'),
      type: 'pages',
    },
    {
      icon: meta.value['files'].icon,
      text: window.panel.$t('files'),
      type: 'files',
    },
  ])

  const page = computed(() => [
    {
      color: getColor(color.pages.base, 1),
      hover: getColor(color.pages.hover, 0.5),
      id: 'pages',
      label: props.page.pages.title,
      nice: props.page.pages.nice,
      size: props.page.pages.size,
      uid: props.page.pages.uid,
      url: props.page.pages.url,
    },
    {
      color: getColor(color.files.base, 1),
      hover: getColor(color.files.hover, 0.5),
      id: 'files',
      label: props.page.files.title,
      nice: props.page.files.nice,
      size: props.page.files.size,
      uid: props.page.files.uid,
      url: props.page.files.url,
    },
  ])

  const pages = computed(() =>
    props.pages.map((item) => ({
      color: getColor(color.pages.base),
      hover: getColor(color.pages.hover, 0.5),
      id: item.page.id,
      label: item.page.title,
      nice: item.page.nice,
      panel: item.page.panel,
      size: item.page.size,
      status: item.page.status,
      type: item.page.type,
      uid: item.page.uid,
      url: item.page.url,
    })),
  )

  const files = computed(() =>
    props.files.map((file) => ({
      color: getColor(color[file.type].base),
      hover: getColor(color[file.type].hover, 0.5),
      id: file.id,
      label: file.title,
      nice: file.nice,
      panel: file.panel,
      size: file.size,
      type: file.type,
      uid: file.uid,
      url: file.url,
    })),
  )

  const all = computed(() => [...pages.value, ...files.value].sort((a, b) => b.size - a.size))

  const dataset = computed(() => {
    const data = {
      all: all.value,
      files: files.value,
      page: page.value,
      pages: pages.value,
    }

    return data[filterBy.value] || []
  })

  const parsed = computed(() => {
    const data = sort?.value === 'asc' ? dataset.value.sort((a, b) => a.size - b.size) : dataset.value.sort((a, b) => b.size - a.size)

    if (search?.value) {
      return data.filter((item) => item.label.toLowerCase().includes(search.value.toLowerCase()))
    }

    return data
  })

  const chunks = computed(() => parsed.value.slice((props.current - 1) * props.limit, props.current * props.limit))
  const length = computed(() => parsed.value.length)

  watch(
    () => props.value,
    (value) => {
      filterBy.value = value
    },
  )
  return {
    chunks,
    dataset,
    length,
    options,
    search,
    sort,
  }
}

function getColor(color, opacity = 1) {
  const hsl = getComputedStyle(document.documentElement).getPropertyValue(color).trim()

  const { h, s, l } = parseToHsl(hsl)
  return `hsla(${h}, ${s}%, ${l}%, ${opacity})`
}

function parseToHsl(color) {
  const values = color.match(/\d+(\.\d+)?/g).map(Number)
  return { h: values[0], s: values[1], l: values[2] }
}
