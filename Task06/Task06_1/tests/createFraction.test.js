import createFraction from '../src/index'

test('toString', () => {
  expect(createFraction(5, 2).toString()).toBe('2\'1/2')
})

test('getNumer', () => {
  expect(createFraction(1, 2).getNumer()).toBe(1)
})

test('getDenom', () => {
  expect(createFraction(1, 2).getDenom()).toBe(2)
})

test('add', () => {
  const frac1 = createFraction(1, 2)
  const frac2 = createFraction(1, 4)
  expect(frac1.add(frac2).toString()).toBe('3/4')
})

test('sub', () => {
  const frac1 = createFraction(3, 5)
  const frac2 = createFraction(1, 5)
  expect(frac1.sub(frac2).toString()).toBe('2/5')
})