export default function createFraction(numerator, denominator) {
  return {
    numerator,
    denominator,

    reduceFraction(numerator, denominator) {
      const greatestCommonDivisor = this.gcd(Math.abs(numerator), denominator)
      if (greatestCommonDivisor == 1) {
        this.numerator = numerator
        this.denominator = denominator
      } else {
        this.numerator = numerator / greatestCommonDivisor
        this.denominator = denominator / greatestCommonDivisor
      }
    },
    gcd (a, b) {
      return b ? this.gcd (b, a % b) : a
    },    
    getNumer() {
      return this.numerator
    },
    getDenom() {
      return this.denominator
    },
    add(frac) {
      return createFraction(
        this.denominator * frac.numerator + this.numerator * frac.denominator,
        this.denominator * frac.denominator
      )
    },
    sub(frac) {
      return createFraction(
        this.numerator * frac.denominator - this.denominator * frac.numerator,
        this.denominator * frac.denominator
      )
    },
    toString() {
      this.reduceFraction(this.numerator, this.denominator)
      if (Math.abs(this.numerator) > this.denominator) {
        const numerator = this.numerator % this.denominator
        const integerPart = Math.sign(this.numerator) * Math.abs((this.numerator - numerator) / this.denominator)
        return `${integerPart}'${numerator}/${this.denominator}`
      }

      return `${this.numerator}/${this.denominator}`
    },
  }
}