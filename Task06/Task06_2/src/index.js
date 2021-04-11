export default function Fraction(numerator, denominator) {
  this.numerator = numerator
  this.denominator = denominator
  this.reduceFraction(numerator, denominator)
}

Fraction.prototype.reduceFraction = function(numerator, denominator) {
  const greatestCommonDivisor = this.gcd(Math.abs(numerator), denominator)
  if (greatestCommonDivisor == 1) {
    this.numerator = numerator
    this.denominator = denominator
  } else {
    this.numerator = numerator / greatestCommonDivisor
    this.denominator = denominator / greatestCommonDivisor
  }
}

Fraction.prototype.gcd  = function (a, b) {
  return b ? this.gcd (b, a % b) : a
}    

Fraction.prototype.getNumer = function () {
  return this.numerator
}

Fraction.prototype.getDenom = function () {
  return this.denominator
}

Fraction.prototype.add = function (frac) {
  return new Fraction(
    this.denominator * frac.numerator + this.numerator * frac.denominator,
    this.denominator * frac.denominator
  )
}

Fraction.prototype.sub = function (frac) {
  return new Fraction(
    this.numerator * frac.denominator - this.denominator * frac.numerator,
    this.denominator * frac.denominator
  )
}

Fraction.prototype.toString = function () {
  if (Math.abs(this.numerator) > this.denominator) {
    const numerator = this.numerator % this.denominator
    const integerPart = Math.sign(this.numerator) * Math.abs((this.numerator - numerator) / this.denominator)
    return `${integerPart}'${numerator}/${this.denominator}`
  }

  return `${this.numerator}/${this.denominator}`
}