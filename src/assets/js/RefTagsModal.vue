<template>
  <!-- overlay -->
  <div
      class="overflow-auto bg-black bg-opacity-50 h-screen w-screen top-0 left-0"
      v-if="on"
      :class="on ? 'fixed inset-0 z-10 flex items-center justify-center' : ''">

    <!-- dialog -->
    <div
        class="model fitted bg-white shadow-2xl m-4 sm:m-8 sm:w-5/6 md:w-auto md:max-w-2/3 w-full mx-4 md:mx-8 relative"

    >
      <div class="header">
        <h1><b>Generate Reference Tag</b></h1>
        <button type="button" @click="$emit('close')" class="absolute top-0 right-0 mr-4 mt-4">✖</button>

      </div>

      <div class="w-full flex flex-row justify-center p-4">
        <ref-tags-form @close="$emit('close')"></ref-tags-form>
      </div>
    </div><!-- /dialog -->

  </div>
  <!-- end overlay -->
</template>

<script>
import RefTagsForm from "./RefTagsForm";

export default {
  name: "RefTagsModal",
  components: {RefTagsForm},
  props: {
    on: {
      type: Boolean
    }
  }
}
</script>

<style scoped>

</style>