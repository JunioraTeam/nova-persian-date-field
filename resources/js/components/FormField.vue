<template>
  <DefaultField :field="currentField" :errors="errors" :show-help-text="showHelpText">
    <template #field>
      <div class="relative flex items-center">
        <span
          @click="show = true"
          v-if="editable"
          class="absolute flex items-center h-full px-3 btn-primary"
          style="border-radius: 0.5rem 0 0 0.5rem; left: 0; top: 0"
        >
        </span>
        <input
          type="text"
          class="w-full form-control form-input form-input-bordered ltr-direction rtl-font-face pl-10"
          :class="editable ? 'is-editable' : ''"
          v-model="value"
          :id="currentField.uniqueKey"
          :name="field.name"
          :dusk="field.attribute"
          :disabled="currentlyIsReadonly"
          :placeholder="placeholder"
          @change="handleChange"
          ref="dateTimePicker"
        />
        <span class="ml-3 mr-3">
          {{ timezone }}
        </span>

        <date-picker
          :clearable="true"
          inputClass="w-full form-control form-input form-input-bordered"
          v-model="value"
          :id="field.attribute"
          :format="format"
          :initial-value="persianDate"
          class="rtl-direction rtl-font-face rtl-font-size"
          :class="errorClasses"
          :color="color"
          :placeholder="placeholder"
          :type="type"
          :element="currentField.uniqueKey"
          :min="minDate"
          :max="maxDate"
          :locale="locale"
          :editable="editable"
          :show="show"
          @close="show = false"
        />
      </div>
    </template>
  </DefaultField>
</template>

<script>
import isNil from "lodash/isNil";
import { DependentFormField, HandlesValidationErrors } from "laravel-nova";
import DatePicker from 'vue3-persian-datetime-picker'
import moment from "moment";
import jMoment from "moment-jalaali";

export default {
  mixins: [HandlesValidationErrors, DependentFormField],

  data: () => ({
    show: false,
  }),

  methods: {
    /*
     * Set the initial value for the field
     */
    setInitialValue() {
      if (!isNil(this.currentField.value)) {
        this.value = this.persianDate;
      }
    },

    /**
     * On save, populate our form data
     */
    fill(formData) {
      this.fillIfVisible(formData, this.field.attribute, this.altDateValue || "");
      // if (this.currentlyIsVisible && filled(this.value)) {}
    },

    /**
     * Update the field's internal value
     */
    handleChange(event) {
      let value = event?.target?.value ?? event;

      // this.value = DateTime.fromISO(value).toISODate();
      this.value = value;

      if (this.field) {
        this.emitFieldValueChange(this.field.attribute, this.value);
      }
    },

    calculateMoment(value, type) {
      var m = new jMoment();
      if (value == "lastyear") {
        m.jYear(m.jYear() - 1);
      } else if (value == "lastmonth") {
        m.jMonth(m.jMonth() - 1);
      } else if (value == "lastweek") {
        m.jDate(m.jDate() - 7);
      } else if (value == "lastday") {
        m.jDate(m.jDate() - 1);
      } else if (value == "today") {
        if (type == "max") {
          m.endOf("day");
        } else {
          m.startOf("day");
        }
      } else if (value == "thisyear") {
        if (type == "max") {
          m.endOf("jyear");
        } else {
          m.startOf("jyear");
        }
      } else if (value == "thismonth") {
        if (type == "max") {
          m.endOf("jmonth");
        } else {
          m.startOf("jmonth");
        }
      } else if (value == "thisweek") {
        if (type == "max") {
          m.endOf("jweek");
        } else {
          m.startOf("jweek");
        }
      } else if (value == "nextyear") {
        m.jYear(m.jYear() + 1);
      } else if (value == "nextmonth") {
        m.jMonth(m.jMonth() + 1);
      } else if (value == "nextweek") {
        m.jDate(m.jDate() + 7);
      } else if (value == "nextday") {
        m.jDate(m.jDate() + 1);
      } else if (value.indexOf("/") !== -1 && jMoment(value, "jYYYY/jMM/jDD").isValid()) {
        m = jMoment(value, "jYYYY/jMM/jDD");
      } else if (value.indexOf("-") !== -1 && jMoment(value, "jYYYY-jMM-jDD").isValid()) {
        m = jMoment(value, "jYYYY-jMM-jDD");
      } else if (
        value.indexOf("/") !== -1 &&
        jMoment(value, "YYYY/MM/DD", true).isValid()
      ) {
        m = jMoment(value, "YYYY/MM/DD");
      } else if (
        value.indexOf("-") !== -1 &&
        jMoment(value, "YYYY-MM-DD", true).isValid()
      ) {
        m = jMoment(value, "YYYY-MM-DD");
      } else {
        m = jMoment(value, "jYYYY-jMM-jDD");
      }
      return m;
    },
  },

  computed: {
    timezone() {
      return Nova.config("userTimezone") || Nova.config("timezone");
    },
    locale() {
      return this.field.locale || "fa,en";
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
          return "jYYYY/jMM/jDD";
      }
      return "jYYYY/jMM/jDD";
    },
    format() {
      if (this.field.formats && this.field.formats.FormField) {
        return this.field.formats.FormField;
      }
      return this.field.format || this.createFormat;
    },
    persianDate() {
      if (!this.field.value) {
        return "";
      } else {
        return jMoment(this.field.value).format(this.format);
      }
    },
    altDateValue() {
        return this.value ? jMoment(this.value, this.format).format('YYYY-MM-DD') : ''
    },
    placeholder() {
      return this.field.placeholder || jMoment().format(this.format);
    },
    altDateValue() {
      return this.value ? jMoment(this.value, this.format).format("YYYY-MM-DD") : "";
    },
    editable() {
      return this.field.editable || false;
    },
    color() {
      return this.field.color || `rgb(30, 136, 229)`;
    },
    fieldId() {
      // if (this.editable) return ''
      var id = this.field.name + "-pdp";
      return id;
    },
    minDate() {
      if (!this.field.min) return "";
      var minDate = this.calculateMoment(this.field.min, "min");
      return minDate.format("jYYYY/jM/jD");
    },
    maxDate() {
      if (!this.field.max) return "";
      var maxDate = this.calculateMoment(this.field.max, "max");
      return maxDate.format("jYYYY/jM/jD");
    },
    type() {
      return this.field.type || "date";
    },
  },
  components: {
    DatePicker,
  },
};

function filled(value) {
  return !isNil(value) && value !== "";
}
</script>
