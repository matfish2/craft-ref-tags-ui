<template>
  <div class="field width-100">
    <div class="heading">
      <label :id="`fields-${name}-label`" :for="`reftags-${name}-dropdown`">{{ label }}</label>
    </div>
    <div>
        <multiselect v-model="selectedValue"
                     @input="$emit('selected', selectedValue.value)"
                     id="ajax"
                     label="name" track-by="value"
                     :placeholder="`Search for ${elementType}`"
                     open-direction="bottom"
                     :options="elements"
                     :multiple="false"
                     :searchable="true"
                     :loading="isLoading"
                     :internal-search="false"
                     :clear-on-select="false"
                     :close-on-select="true" :options-limit="10"
                     :max-height="600"
                     :show-no-results="true"
                     :hide-selected="true"
                     @search-change="asyncFind">
          <span slot="noResult">Oops! No elements found.</span>
        </multiselect>
      </div>
  </div>

</template>

<script>
import Multiselect from 'vue-multiselect'
require('vue-multiselect/dist/vue-multiselect.min.css')
export default {
  name: "SearchField",
  components: {Multiselect},
  props: {
    name: {},
    label: {},
    value: {},
    elementType:{},
    siteId:{},
    qualifiers:{
      type:Object
    }
  },
  data() {
    return {
      selectedValue: '',
      elements: [],
      isLoading: false
    }
  },
  methods: {
    async asyncFind(query) {
      this.isLoading = true
      const {data} = await axios.get(`/?action=ref-tags-ui/ref-tags/elements&q=${query}&elementType=${this.elementType}&siteId=${this.siteId}&qualifiers=${JSON.stringify(this.qualifiers)}`)
      this.elements = data
      this.isLoading = false
    },
    reset() {
      this.selectedValue = null
    }
  },
  computed: {}
}
</script>

<style scoped>
  .field {
    margin-left:0 !important;
    margin-right:0 !important;

  }

  >>> .multiselect {
    max-width:500px;
  }
</style>