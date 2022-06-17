<template>
 <div id="reftags-element-dropdown-wrapper" class="field width-100">
      <div class="heading">
        <label :id="`fields-${name}-label`" :for="`reftags-${name}-dropdown`">{{label}}</label>
      </div>
      <div class="input ltr">
        <div class="select">
          <select :id="`reftags-${name}-dropdown`" :name="`reftag_${name}`" :value="value"  @input="(event) => $emit('input', event.target.value)">
            <option v-if="placeholder" value="">{{placeholder}}</option>
            <option v-for="item in items" :value="item.value">{{ item.label }}</option>
          </select>
        </div>
      </div>
    </div>
</template>

<script>
export default {
  name: "DropdownField",
  props:{
    label:{
      type:String,
      required:true
    },
    name:{
      type:String,
      required:true
    },
    placeholder:{},
    items:{
      type:Array,
      required:true
    },
    value:{

    }
  }
}
</script>

<style scoped>
.field {
  margin-left:0 !important;
}
</style>