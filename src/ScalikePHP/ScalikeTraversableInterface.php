<?php
namespace ScalikePHP;

/**
 * Scala like Traversable Interface
 */
interface ScalikeTraversableInterface extends \ArrayAccess, \Countable, \IteratorAggregate, \JsonSerializable
{

    /**
     * 値の全要素に対して関数を適用する
     *
     * @param callable $f 関数
     * @return void
     */
    public function each(callable $f);

    /**
     * 条件にマッチする（関数が true を返す）要素の有無を判定する
     *
     * @param callable $f 真偽値を返す関数
     * @return bool 条件を満たす要素がある場合に true, そうでない場合は false
     */
    public function exists(callable $f);

    /**
     * 条件にマッチする（関数が true を返す）要素のみの配列(Seq)を返す
     *
     * @param callable $f 真偽値を返す関数
     * @return ScalikeTraversableInterface
     */
    public function filter(callable $f);

    /**
     * 条件にマッチしない（関数が false を返す）要素のみの配列(Seq)を返す
     *
     * @param callable $f 真偽値を返す関数
     * @return ScalikeTraversableInterface
     */
    public function filterNot(callable $f);

    /**
     * 条件にマッチする（関数が true を返す）最初の要素を返す
     *
     * @param callable $f 真偽値を返す関数
     * @return Option 最初に見つかった要素, 見つからなかった場合は None
     */
    public function find(callable $f);

    /**
     * 値の全要素に対して関数を適用し、その戻り値を平坦化して返す
     *
     * @param callable $f 値を返す関数
     * @return ScalikeTraversableInterface
     */
    public function flatMap(callable $f);

    /**
     * 全ての要素が条件にマッチする（関数が true を返す）かどうかを判定する
     *
     * @param callable $f 真偽値を返す関数
     * @return bool 全ての要素が条件を満たす場合に true, そうでない場合は false
     */
    public function forAll(callable $f);

    /**
     * 値の先頭要素を返す, 要素がない場合は例外を投げる
     *
     * @return mixed 先頭の要素
     */
    public function head();

    /**
     * 値の先頭要素を返す
     *
     * @return Option 先頭の要素, 要素がない場合は None
     */
    public function headOption();

    /**
     * 値が空かどうかを判定する
     *
     * @return bool 値が空の場合に true, そうでない場合に false
     */
    public function isEmpty();

    /**
     * 値の末尾(最終)要素を返す, 要素がない場合は例外を投げる
     *
     * @return mixed 末尾の要素
     */
    public function last();

    /**
     * 値の末尾(最終)要素を返す
     *
     * @return Option 末尾の要素, 要素がない場合は None
     */
    public function lastOption();

    /**
     * 値の全要素に対して関数を適用し、その戻り値を返す
     *
     * @param callable $f 値を返す関数
     * @return ScalikeTraversableInterface
     */
    public function map(callable $f);

    /**
     * 値が空でないかどうかを判定する
     *
     * @return bool 値が空でない場合に true, そうでない場合に false
     */
    public function nonEmpty();

    /**
     * 値の要素数を返す
     *
     * @return int 値の要素数
     */
    public function size();

    /**
     * 値の先頭 n 個の要素を返す
     *
     * @param int $n 取得する要素の数
     * @return ScalikeTraversableInterface|Seq
     */
    public function take($n);

    /**
     * 値の末尾 n 個の要素を返す
     *
     * @param int $n 取得する要素の数
     * @return ScalikeTraversableInterface|Seq
     */
    public function takeRight($n);

    /**
     * 配列に変換する
     *
     * @return array
     */
    public function toArray();

    /**
     * Seq に変換する
     *
     * @return Seq
     */
    public function toSeq();

}