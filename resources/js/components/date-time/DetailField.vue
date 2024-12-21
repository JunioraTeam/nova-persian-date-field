<template>
  <PanelItem :index="index" :field="fieldPersianDate">
    <template #value>
      <span v-if="fieldPersianDate.value">
        <span v-if="field.humanize" class="inline-flex items-center">
          <span class="inline-block">{{ formattedDate }}</span>
          <span class="ltr inline-flex items-center text-gray-400 text-xs ml-3">
            <span class="mr-1 ml-1 ltr inline-block">{{ formattedValue }}</span>
          </span>
        </span>
        <span v-else class="ltr inline-block">{{ fieldPersianDate.value }}</span>
      </span>
      <span v-else>&mdash;</span>
    </template>
  </PanelItem>
</template>

<script>
import jMoment from "moment-jalaali";

export default {
  props: ["index", "resource", "resourceName", "resourceId", "field"],

  computed: {
    fieldPersianDate() {
      this.field.asHtml = true;
      return this.field;
    },
    formattedDate() {
      if (this.field.usesCustomizedDisplay) {
        return this.field.value;
      }

      if (this.field.value) {
        var d = jMoment(this.field.value);
        if (this.field.humanize && this.canHumanize) {
          return d.fromNow();
        }
        if (this.timezone) {
          d.tz(this.timezone);
        }
        return d.format(this.format);
      }
      return "-";
    },
    formattedValue() {
      if (this.field.value) {
        var d = jMoment(this.field.value);
        if (this.timezone) {
          d.tz(this.timezone);
        }
        return d.format(this.format);
      }
    },
    format() {
      if (this.field.formats && this.field.formats.DetailField) {
        return this.field.formats.DetailField;
      }
      return this.field.format || this.createFormat();
    },
    type() {
      return this.field.type || "datetime";
    },
    moment() {
      return jMoment(this.field.value);
    },
    canHumanize() {
      if (
        this.type == "time" ||
        this.type == "year-month" ||
        this.type == "year" ||
        this.type == "month"
      ) {
        return false;
      }
      return true;
    },
  },
  methods: {
    persianDate() {
      if (this.field.value) {
        // var d = jMoment(this.field.value)
        var d = this.moment;
        if (this.field.humanize && this.canHumanize) {
          return d.fromNow();
        }
        return d.format(this.format);
      }
      return "-";
    },
    createFormat() {
      switch (this.type) {
        case "date":
          return "jYYYY/jMM/jDD";
        case "time":
          return "HH:mm";
        case "year-month":
          return "jYYYY/jMM";
        case "year":
          return "jYYYY";
        case "month":
          return "jMM";
        case "datetime":
          return "jYYYY/jMM/jDD HH:mm";
        case "full":
          return "jYYYY/jMM/jDD HH:mm:ss";
      }
      return "jYYYY/jMM/jDD HH:mm";
    },
  },
};
</script>
