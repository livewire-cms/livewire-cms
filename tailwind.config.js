const defaultTheme = require('tailwindcss/defaultTheme')
const plugin = require('tailwindcss/plugin')
const Color = require('color')
const colors = require('./colors')
const tailcolors = require('tailwindcss/colors')

module.exports = {

  mode: 'jit',

  purge: [
      './Modules/*/Resources/views/**/*.blade.php',
      './resources/views/**/*.blade.php',
       './resources/css/**/*.css',
       './vendor/ph7jack/wireui/resources/**/*.blade.php',
        './vendor/ph7jack/wireui/ts/**/*.ts',
        './vendor/ph7jack/wireui/src/View/**/*.php'

    ],
  theme: {
    themeVariants: ['dark'],
    customForms: (theme) => ({
      default: {
        'input, textarea': {
          '&::placeholder': {
            color: theme('colors.gray.400'),
          },
        },
      },
    }),
    ...colors,
    extend: {
        colors: {
            blueGray: tailcolors.blueGray,
            primary: tailcolors.indigo,
            secondary: tailcolors.gray,
            positive: tailcolors.emerald,
            negative: tailcolors.red,
            warning: tailcolors.amber,
            info: tailcolors.blue
        },
      maxHeight: {
        0: '0',
        xl: '36rem',
      },
      fontFamily: {
        sans: ['Inter', ...defaultTheme.fontFamily.sans],
      },
    },
  },

  variants: {
    opacity: ['responsive', 'hover', 'focus', 'disabled'],
    backgroundColor: [
      'hover',
      'focus',
      'active',
      'odd',
      'dark',
      'dark:hover',
      'dark:focus',
      'dark:active',
      'dark:odd',
      'dark:disabled',
    ],
    display: ['responsive', 'dark'],
    textColor: [
      'focus-within',
      'hover',
      'active',
      'dark',
      'dark:focus-within',
      'dark:hover',
      'dark:active',
    ],
    placeholderColor: ['focus', 'dark', 'dark:focus'],
    borderColor: ['focus', 'hover', 'dark', 'dark:focus', 'dark:hover'],
    divideColor: ['dark'],
    boxShadow: ['focus', 'dark:focus'],
  },

  plugins: [
    require('@tailwindcss/ui'),
    require('tailwindcss-multi-theme'),
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    plugin(({ addUtilities, e, theme, variants }) => {
      const newUtilities = {}
      Object.entries(theme('colors')).map(([name, value]) => {
        if (name === 'transparent' || name === 'current') return
        const color = value[300] ? value[300] : value
        const hsla = Color(color).alpha(0.45).hsl().string()

        newUtilities[`.shadow-outline-${name}`] = {
          'box-shadow': `0 0 0 3px ${hsla}`,
        }
      })

      addUtilities(newUtilities, variants('boxShadow'))
    }),
    plugin(function ({ addUtilities }) {
        const utility = {
          '.hide-scrollbar::-webkit-scrollbar': {
            'display': 'none'
          },
          '.hide-scrollbar': {
            '-ms-overflow-style': 'none',
            'scrollbar-width': 'none'
          }
        }

        addUtilities(utility, ['responsive'])
    })
  ],
}
