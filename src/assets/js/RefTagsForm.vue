<template>
  <div class="ref-tags-form-wrapper">
    <form>
      <dropdown-field name="siteId" v-model="form.siteId" :items="sites" label="Site"/>
      <dropdown-field name="elementType" v-model="form.elementType" :items="elementTypes" label="Element Type"/>
      <dropdown-field v-if="form.elementType==='globalset'" name="globalsets" v-model="form.elementId"
                      :items="globalsets" label="Global Set"/>
      <dropdown-field v-if="form.elementType==='globalset'" placeholder="Select Field" name="globalsetfields"
                      v-model="form.elementProperty"
                      :items="globalSetFields" label="Global Field"/>

      <div v-if="form.elementType!=='globalset'">
        <div v-if="form.elementType==='entry'" class="entry-qualifiers">
          <dropdown-field name="sectionId"
                          label="Section"
                          v-show="sections.length"
                          v-model="form.qualifiers.sectionId"
                          placeholder="Select Section"
                          :items="sections"
          />
          <dropdown-field name="entryTypeId"
                          label="Entry Type"
                          v-if="form.qualifiers.sectionId && form.qualifiers.sectionId"
                          v-model="form.qualifiers.typeId"
                          placeholder="Select Entry Type"
                          :items="entryTypes"
          />
        </div>

        <div v-if="form.elementType==='asset'">
          <dropdown-field name="volumeId"
                          label="Asset Volume"
                          v-model="form.qualifiers.volumeId"
                          placeholder="Select Volume"
                          :items="volumes"
          />
        </div>

        <div v-if="form.elementType==='category'">
          <dropdown-field name="groupId"
                          label="Category Group"
                          v-model="form.qualifiers.groupId"
                          placeholder="Select Category Group"
                          :items="categoryGroups"
          />
        </div>

        <div v-if="form.elementType==='tag'">
          <dropdown-field name="groupId"
                          label="Tag Group"
                          v-model="form.qualifiers.groupId"
                          placeholder="Select Tag Group"
                          :items="tagGroups"
          />
        </div>

        <search-field
            v-if="!reloading && hasQualifiers"
            :label="ucfirst(form.elementType)"
            name="element"
            ref="element"
            :value="form.elementId"
            :element-type="form.elementType"
            :qualifiers="form.qualifiers"
            :site-id="form.siteId"
            @selected="v=>form.elementId=v"/>

        <dropdown-field v-show="properties.length>0"
                        name="property"
                        placeholder="Select Field"
                        class="highlighter p-1"
                        v-model="form.elementProperty"
                        label="Field"
                        :items="properties"/>

      </div>

      <div id="copy-input-wrapper" class="copytextbtn code small light highlighter" role="button"
           title="Copy to clipboard"
           aria-label="Copy to clipboard" style="padding:10px;" tabindex="0" @click="copy" v-if="ref.length>0">
        <input id="copy-input" :value="ref" readonly="readonly" size="50"
               style="font-size:20px !important;"
               tabindex="-1"><span data-icon="clipboard"
                                   aria-hidden="true"></span>
      </div>
    </form>
  </div>
</template>

<script>
import DropdownField from "./DropdownField";
import SearchField from "./SearchField";

export default {
  name: "RefTagsForm",
  components: {DropdownField, SearchField},
  data() {
    return {
      sites: [],
      globalsets: [],
      properties: [],
      sections: [],
      volumes: [],
      categoryGroups: [],
      tagGroups: [],
      reloading: false,
      elementTypes: [
        {
          value: 'entry',
          label: 'Entry'
        },
        {
          value: 'asset',
          label: 'Asset'
        },
        {
          value: 'category',
          label: 'Category'
        },
        {
          value: 'tag',
          label: 'Tag'
        },
        {
          value: 'user',
          label: 'User'
        },
        {
          value: 'globalset',
          label: 'Global Set'
        },
      ],
      form: {
        siteId: null,
        elementType: 'globalset',
        elementId: null,
        elementProperty: null,
        qualifiers: {
          sectionId: null,
          typeId: null,
          volumeId: null,
          groupId: null
        }
      }
    }
  },
  async mounted() {
    const {data} = await axios.get('/?action=ref-tags-ui/ref-tags/initial-data');
    this.sites = data.sites.map(site => ({value: site.id, label: site.name}))
    await this.$nextTick(() => {
      this.form.siteId = data.currentSiteId
    })
    this.globalsets = data.globalSets
    this.form.elementId = this.globalsets[0].value

    this.$watch('form.elementType', () => {
      this.resetForm()
      const type = this.form.elementType
      if (type === 'user') {
        this.reloading = true
        this.populatePropertiesList()
        this.reloading = false
      } else if (type !== 'globalset') {
        if (this.$refs.element) {
          this.$refs.element.reset()
        }
        this.properties = []
        if (type === 'entry') {
          this.populateSections()
        } else if (type === 'asset') {
          this.populateAssetVolumes()
        } else if (type === 'category') {
          this.populateCategoryGroups()
        } else if (type === 'tag') {
          this.populateTagGroups()
        }
      }
    })

    this.$watch('form.qualifiers', () => {
      if (this.form.elementType === 'entry' && this.form.qualifiers.sectionId && this.form.qualifiers.typeId) {
        this.populatePropertiesList({
          sectionId: this.form.qualifiers.sectionId,
          typeId: this.form.qualifiers.typeId
        })
      } else if (this.form.elementType === 'asset' && this.form.qualifiers.volumeId) {
        this.populatePropertiesList({
          volumeId: this.form.qualifiers.volumeId,
        })
      } else if (['category', 'tag'].includes(this.form.elementType) && this.form.qualifiers.groupId) {
        this.populatePropertiesList({
          groupId: this.form.qualifiers.groupId,
        })
      } else {
        this.properties = []
      }
    }, {deep: true})
  },
  methods: {
    resetForm() {
      this.form.elementId = null
      this.form.elementProperty = null
      this.form.qualifiers.sectionId = null
      this.form.qualifiers.typeId = null
      this.form.qualifiers.volumeId = null
      this.form.qualifiers.groupId = null

      if (this.form.elementType === 'globalset') {
        this.form.elementId = this.globalsets[0].value
      }
    },
    ucfirst(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    },
    copy() {
      $("#copy-input").select()
      document.execCommand('copy')
      Craft.cp.displayNotice("Copied Reference Tag to clipboard.");
      this.$emit('close')
    },
    async populatePropertiesList(qualifiers = {}) {
      const {data} = await axios.get('/?action=ref-tags-ui/ref-tags/properties-list&elementType=' + this.form.elementType + '&qualifiers=' + JSON.stringify(qualifiers))
      this.properties = data
    },
    async populateSections() {
      const {data} = await axios.get('/?action=ref-tags-ui/ref-tags/sections')
      this.sections = data
    },
    async populateAssetVolumes() {
      const {data} = await axios.get('/?action=ref-tags-ui/ref-tags/volumes')
      this.volumes = data
    },
    async populateCategoryGroups() {
      const {data} = await axios.get('/?action=ref-tags-ui/ref-tags/category-groups')
      this.categoryGroups = data
    },
    async populateTagGroups() {
      const {data} = await axios.get('/?action=ref-tags-ui/ref-tags/tag-groups')
      this.tagGroups = data
    }
  },
  computed: {
    globalSetFields() {
      if (this.form.elementType === 'globalset' && this.form.elementId) {
        return this.globalsets.find(set => set.value === this.form.elementId).fields
      }

      return []
    },
    hasQualifiers() {
      switch (this.form.elementType) {
        case 'entry':
          return !!this.form.qualifiers.sectionId
        case 'category':
        case 'tag':
          return !!this.form.qualifiers.groupId
        case 'asset':
          return !!this.form.qualifiers.volumeId
        case 'user':
          return true
        default:
          return false
      }
    },
    entryTypes() {
      if (this.form.qualifiers.sectionId) {
        return this.sections.find(section => parseInt(section.value) === parseInt(this.form.qualifiers.sectionId)).entryTypes
      }

      return []
    },
    ref() {
      let ref = ''

      if (this.form.siteId && this.form.elementType && this.form.elementId) {
        ref = '{' + this.form.elementType + ':' + this.form.elementId + '@' + this.form.siteId
        if (this.form.elementProperty) {
          ref += ':' + this.form.elementProperty
        }

        ref += '}'
      }

      return ref

    }
  }
}
</script>

<style scoped>

.copy-to-clipboard {
  cursor: pointer;
}

.highlighter {
  animation: fadeoutBg 3s; /***Transition delay 3s fadeout is class***/
  -moz-animation: fadeoutBg 3s; /* Firefox */
  -webkit-animation: fadeoutBg 3s; /* Safari and Chrome */
  -o-animation: fadeoutBg 3s; /* Opera */
}

@keyframes fadeoutBg {
  from {
    background-color: lightgreen;
  }
  /** from color **/
  to {
    background-color: white;
  }
  /** to color **/
}

@-moz-keyframes fadeoutBg { /* Firefox */
  from {
    background-color: lightgreen;
  }
  to {
    background-color: white;
  }
}

@-webkit-keyframes fadeoutBg { /* Safari and Chrome */
  from {
    background-color: lightgreen;
  }
  to {
    background-color: white;
  }
}

@-o-keyframes fadeoutBg { /* Opera */
  from {
    background-color: lightgreen;
  }
  to {
    background-color: white;
  }
}

@media (min-width: 600px) {
  .ref-tags-form-wrapper {
    width: 600px;
  }
}

</style>