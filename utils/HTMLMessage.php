<?php 
namespace Utils;

$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once($ROOT . "/vendor/autoload.php");

$dotenv = \Dotenv\Dotenv::createImmutable($ROOT);
$dotenv->safeLoad();

class HTMLMessage{
    public function joinStockvelMessage($member_name = null){
        // [valued subscriber/Name]
        $joinStockvelMsg = "
                            <div class='join_stockvel'>
                                <p>Dear $member_name,</P>
                                <br/>
                                <p>Congratulations on taking the first step towards financial success by joining Stockvel, the premier money pooling and saving tool. We are excited to have you as a member of our community and are committed to helping you reach your financial goals.</p>
                                <p>At Stockvel, we believe in the power of collective saving and investing, and our platform is designed to make it easy for you to manage and grow your wealth. With a range of saving options and tools at your disposal, you can take control of your financial future and make informed decisions about your money, in a growing community that thrives together. </p>
                                <p>As a member, you will have access to the following features and benefits:</p>
                                <ul>
                                    <li>A customizable money pooling pack that you can create, manage and make available to your networks to join and build wealth with you</li>
                                    <li>Convenient tools to schedule, carry and join virtual meeting with other stockvelers you join your pack</li>
                                    <li>Simplified and powerful accounting tools to track, manage and report during the life cycle of the pack</li>
                                    <li>Educational and financial literacy resources to help you make informed decisions about your money</li>
                                    <li>A supportive community of like-minded individuals who are also working towards financial success</li>
                                </ul>
                                <p>We are committed to providing you with the best possible experience on Stockvel and welcome any feedback or sugge</p>
                                <p> feedback or suggestions you may have. If you have any questions or need assistance, please don't hesitate to reach out to us.</p>
                                <p> Thank you for choosing Stockvel and we look forward to helping you achieve financial success.</p>
                                <br />
                                <br />
                                <p> Sincerely,</p>
                                <p> [Chief Stockveler Happiness Officer]</p>
                                <p> Stockvel Team</p>
                            </div>
        ";
        return $joinStockvelMsg;
    }

    public function becomeOwner($leader_name){
        $admin_name = $_ENV["ADMIN_NAME"];
        // [Name/ pack leader/owner]
        $brcomerOwnerMsg = "
        <p> Dear $leader_name, </p>
        <br />
        <br />
        <p> Congratulations on starting your own money pooling pack with Stockvel! We are thrilled to have you as a part of our community and look forward to helping you achieve your financial goals. </p>
        <p> As a money pooling pack owner, you will have access to a range of exclusive benefits and features, including: </p>
        <ul>
            <li> The ability to invite other members to join your money pooling pack and collaborate on investment decisions </li>
            <li> Advanced investment options and strategies </li>
            <li> Customized reports and analysis to track the performance of your money pooling pack </li>
            <li> Priority support from our team  </li>
        </ul>
        <p> We encourage you to take full advantage of these resources and make the most of your money pooling pack. Don't hesitate to reach out to us if you have any questions or need assistance. </p>
        <p> Thank you for choosing Stockvel and we look forward to your continued success on our platform. </p>
        <br />
        <br />
        <p> Sincerely, </p>
        <p> $admin_name </p>
        <p> Stockvel Team </p>
        ";
        return $brcomerOwnerMsg;
    }

    public function withdrawMemberMsg($member_name, $amount, $today_date, $total_amount, $member_num, $num_of_withdraw, $leader_name, $total_member){
        $msgString = "
            <p> This Participation Certificate is presented to: </p>
            <p> $member_name </p>
            <p> For contributing the sum of $amount to the Stockvel money pool on 
            Your rotation order for withdrawals is $num_of_withdraw. You have cashed a total of $total_amount to date. 
            This contribution represents $member_num out of $total_member total participations.
            To date, you have not been deficient in your participation in the money pool.
            We are grateful for your commitment to the money pool and look forward to working with you to achieve your financial goals.
            This Certificate is issued as evidence of the Member's participation in the money pool and shall remain in effect until the expiration of the term of the money pool agreement or until the Member's participation is terminated.
            IN WITNESS WHEREOF, the money pool leader has executed this Certificate as of the date of acceptance. </p>
            <br />
            <p>$leader_name</p>
            <P>$today_date</P>
            <p>Money Pool Leader</p>            
        ";
        return $msgString;
    }


    public function packClosingSms($member_name = null, $leader_name=null, $today_date = null, $total_amount=null, $member_num, $total_members=null){
        $msgString = "
        <p>This Participation Certificate ('Certificate') is issued to $member_name on the date of acceptance and is by and between $leader_name, a money pack owner, and the Member.</p>
        <p><b>AMOUNT CONTRIBUTED:</b> The Member has contributed the sum of $total_amount to the money pool.</p>
        <p><b>DATE:</b> This Certificate is issued on $today_date.</p>
        <p><b>COUNTER OF NUMBER OF PARTICIPATION LEFT:</b> This contribution represents $member_num out of $total_members total participation.
        To date, you have not been deficient in your participation in the money pool.<p>
        <br />
        <p>This Certificate is issued as evidence of the Member's participation in the money pool and shall remain in effect until the expiration of the term of the money pool agreement or until the Member's participation is terminated.
        IN WITNESS WHEREOF, the money pool leader has executed this Certificate as of the date of acceptance. </p>
        <p>$leader_name</p>
        <P>$today_date</p>       
        ";
        return $msgString;
    }

    public function agreementDefault($leader_name = ""){
        $agreement_date = date('Y-m-d'); 
        $htmlStr = "
            <h2>MONEY POOL AGREEMENT</h2>
            <br/>
            <p>This Money Pool Agreement ('Agreement') is made and entered into on the date of acceptance by the [member] ('Member') and is by and between [Money Pool Leader], a money pack owner, and the Member.</p>
            <br/>
            <p><b>PURPOSE OF MONEY POOL:</b> The purpose of the money pool is to pool funds from Members in order to save collectively and [Purpose].</P>
            <p><b>AMOUNT OF COMMITMENT:</b> Each Member shall commit a minimum of [amount] to the money pool.</p>
            <p><b>TERM OF COMMITMENT:</b> The term of commitment for each Member shall be [term].</p>
            <p><b>ROTATION PROCEDURE FOR WITHDRAWAL:</b> Members may request to withdraw funds from the money pool according to the following rotation procedure: [procedure].</p>
            <p><b>FREQUENCY OF WITHDRAWAL:</b> Members may request to withdraw funds from the money pool [frequency].</p>
            <p><b>PERIODIC REPORTS:</b> [Money Pool Leader] shall provide periodic reports to Members detailing the performance of the money pool and its investments.</p>
            <p><b>EXIGIBLE ACCOUNTING DOCUMENTATION:</b> The [Money Pool Leader]  shall maintain accurate and complete financial records and shall make such records available to the Members upon request.</p>
            <p><b>DEFICIENT PARTICIPATION AND REMEDY:</b> In the event that a Member fails to make the required contributions to the money pool, the  [Money Pool Leader] may take such actions as deemed necessary to remedy the deficiency, including but not limited to suspending the Member's rights and privileges under this Agreement.</p>
            <p><b>ADMISSIBLE USES OF FUNDS:</b> Funds in the money pool may only be used for investment and saving purposes. Any other use of funds is strictly prohibited.</p>
            <p><b>RIGHTS OF MEMBERS:</b> Each Member has the right to request information about the money pool and its investments, as well as the right to request a withdrawal of funds subject to the availability of funds and the approval of the [Money Pool Leader].</p>
            <p><b>DISCLAIMER OF LIABILITY:</b> Stockvel shall not be liable for any losses or damages sustained by Members as a result of their participation in the money pool. Each Member acknowledges and agrees that they are participating in the money pool at their own risk.</p>
            <p><b>MISCELLANEOUS PROVISIONS:</b> This Agreement constitutes the entire agreement between the parties and supersedes all prior agreements or understandings, whether written or oral. This Agreement may not be amended or modified except in writing signed by both parties.</p>
            <p><b>GOVERNING LAW:</b> This Agreement shall be governed by and construed in accordance with the laws of the State of [State].</p>
            <p>IN WITNESS WHEREOF, both parties have executed this Agreement as of the date of acceptance.</p>
            <br/>
            <br/>
            <p>$leader_name</p>
            <p>$agreement_date</p>
            <p>Money Pool Leader</p>
        ";
        return $htmlStr;
    }
}
?>