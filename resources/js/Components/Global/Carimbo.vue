<script setup>
const props = defineProps({
  title: {
    type: String,
    required: true
  },
  type: {
    type: String,
    default: ''
  },
  size: {
    type: Number,
    default: 9
  },
  rotate: {
    type: Number,
    default: 12
  }
})

const setSize = () => {
  return {
    fontSize: props.size / 4 + 'rem',
    width: props.size + 'rem',
    height: props.size / 4 + 'rem'
  }
}
</script>

<template>
  <span
    class="stamp"
    :style="[
      setSize()['fontSize'],
      setSize()['width'],
      setSize()['height'],
      `transform: rotate(${rotate}deg)`
    ]"
    :class="[
      type === 'approved' ? 'is-approved' : '',
      type === 'nope' ? 'is-nope' : '',
      type === 'caution' ? 'is-caution' : '',
      type === 'draft' ? 'is-draft' : ''
    ]"
  >{{ title }}</span>
</template>

<style scoped>
body,
html {
  margin: 0px;
  overflow: hidden;
  display: flex;
  justify-content: space-around;
  align-items: center;
  flex-direction: row;
}
.stamp {
  color: #555;
  font-weight: 700;
  border: 0.25rem solid #555;
  display: inline-block;
  padding: 0.25rem 1rem;
  text-transform: uppercase;
  font-family: 'Courier';
  border-radius: 0.5rem;
  -webkit-mask-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/8399/grunge.png');
  -webkit-mask-size: 944px 604px;
  mix-blend-mode: multiply;
}

.is-nope {
  color: #d23;
  border: 0.5rem double #d23;
  -webkit-mask-position: 2rem 3rem;
  font-size: 2rem;
}

.is-approved {
  color: #0a9928;
  border: 0.5rem solid #0a9928;
  -webkit-mask-position: 13rem 6rem;
}

.is-draft {
  color: #c4c4c4;
  border: 1rem double #c4c4c4;
  font-size: 6rem;
  font-family: 'Open sans', Helvetica, Arial, sans-serif;
  padding: 0.5rem;
}

.is-caution {
  color: #f90;
  border: 0.5rem solid #f90;
  -webkit-mask-position: 6rem 6rem;
}
</style>
