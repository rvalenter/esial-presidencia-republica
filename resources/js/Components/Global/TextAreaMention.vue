<template>
  <div class="mention-textarea">
    <textarea
      ref="textarea"
      v-model="content"
      class="w-full bg-gray-50 shadow-md px-2 py-0.5 border-transparent focus:ring-2 ring-blue-600 mt-0.5 rounded-lg"
      @input="onInput"
      @keydown="onKeydown"
      @blur="hideSuggestions"
    />
    <ul
      v-if="showSuggestions"
      class="suggestions-list shadow-lg -mt-0.5"
    >
      <li
        v-for="(suggestion, index) in filteredSuggestions"
        :key="index"
        :class="{ highlighted: index === highlightedIndex }"
        @mousedown.prevent="selectSuggestion(suggestion)"
      >
        {{ suggestion }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import {nextTick, ref} from 'vue';

const content = ref("");
const textarea = ref(null);
const showSuggestions = ref(false);
const highlightedIndex = ref(0);
const mentionStartIndex = ref(null);

const suggestions = ["Alice", "Bob", "Charlie", "Dave"];
const filteredSuggestions = ref([]);

const onInput = () => {
    const cursorPos = textarea.value.selectionStart;
    const text = content.value.slice(0, cursorPos);
    const mentionIndex = text.lastIndexOf("@");

    if (mentionIndex !== -1) {
        mentionStartIndex.value = mentionIndex;
        const query = text.slice(mentionIndex + 1);

        filteredSuggestions.value = suggestions.filter((suggestion) =>
            suggestion.toLowerCase().startsWith(query.toLowerCase())
        );

        showSuggestions.value = filteredSuggestions.value.length > 0;
    } else {
        showSuggestions.value = false;
    }
};

const onKeydown = (event) => {
    if (showSuggestions.value) {
        switch (event.key) {
            case "ArrowDown":
                highlightedIndex.value =
                    (highlightedIndex.value + 1) % filteredSuggestions.value.length;
                event.preventDefault();
                break;
            case "ArrowUp":
                highlightedIndex.value =
                    (highlightedIndex.value + filteredSuggestions.value.length - 1) %
                    filteredSuggestions.value.length;
                event.preventDefault();
                break;
            case "Enter":
                selectSuggestion(filteredSuggestions.value[highlightedIndex.value]);
                event.preventDefault();
                break;
            case "Escape":
                showSuggestions.value = false;
                break;
        }
    }
};

const selectSuggestion = (suggestion) => {
    const cursorPos = textarea.value.selectionStart;
    const textBeforeMention = content.value.slice(0, mentionStartIndex.value);
    const textAfterMention = content.value.slice(cursorPos);

    content.value = `${textBeforeMention}@${suggestion} ${textAfterMention}`;
    showSuggestions.value = false;
    nextTick(() => {
        textarea.value.selectionStart =
            textarea.value.selectionEnd = textBeforeMention.length + suggestion.length + 2;
        textarea.value.focus();
    });
};

const hideSuggestions = () => {
    setTimeout(() => {
        showSuggestions.value = false;
    }, 100);
};
</script>

<style scoped>
.mention-textarea {
    position: relative;
}

.suggestions-list {
    position: absolute;
    background: white;
    border: 1px solid #ccc;
    list-style: none;
    padding: 0;
    margin: 0;
    width: 100%;
    border-radius: 10px;
}

.suggestions-list li {
    padding: 8px;
    cursor: pointer;
}

.suggestions-list li.highlighted {
    background-color: #eaeaea;
}
</style>
