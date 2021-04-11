export default class Fraction {
  constructor(numerator, denominator) {
    this.numerator = numerator
    this.denominator = denominator
    this.reduceFraction(numerator, denominator)
  }

  reduceFraction(numerator, denominator) {
    const greatestCommonDivisor = this.gcd(Math.abs(numerator), denominator)
    if (greatestCommonDivisor == 1) {
      this.numerator = numerator
      this.denominator = denominator
    } else {
      this.numerator = numerator / greatestCommonDivisor
      this.denominator = denominator / greatestCommonDivisor
    }
  }
  
  gcd(a, b) {
    return b ? this.gcd (b, a % b) : a
  }    

  get numer() {
    return this.numerator
  }

  get denom() {
    return this.denominator
  }

  add(frac) {
    return new Fraction(
      this.denominator * frac.numerator + this.numerator * frac.denominator,
      this.denominator * frac.denominator
    )
  }

  sub(frac) {
    return new Fraction(
      this.numerator * frac.denominator - this.denominator * frac.numerator,
      this.denominator * frac.denominator
    )
  }

  toString() {
    if (Math.abs(this.numerator) > this.denominator) {
      const numerator = this.numerator % this.denominator
      const integerPart = Math.sign(this.numerator) * Math.abs((this.numerator - numerator) / this.denominator)
      return `${integerPart}'${numerator}/${this.denominator}`
    }
  
    return `${this.numerator}/${this.denominator}`
  }
}